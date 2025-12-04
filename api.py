from flask import Flask, jsonify, render_template_string, request
from datetime import datetime
import locale
import json
import requests     # <-- needed to POST to PHP
import paho.mqtt.client as mqtt
from paho.mqtt import publish
import threading
from flask_cors import CORS

app = Flask(__name__)
CORS(app)

MQTT_HOST = "test.mosquitto.org"
MQTT_PORT = 1883
MQTT_TOPIC = "ac/actuador"

# Configura el idioma español
try:
    locale.setlocale(locale.LC_TIME, 'es_ES.UTF-8')
except:
    locale.setlocale(locale.LC_TIME, 'es_MX.UTF-8')

# --- MQTT: variables y lock para acceso seguro ---
last_message = None
_last_message_lock = threading.Lock()

PHP_URL = "http://localhost/InvernaTec/api_data.php"   # <-- change if needed


def on_connect(client, userdata, flags, rc):
    print("MQTT conectado, rc=", rc)
    client.subscribe("ac/sensor")


def on_message(client, userdata, msg):
    global last_message
    payload = msg.payload.decode(errors='ignore')

    # Guardar el último mensaje
    with _last_message_lock:
        last_message = {
            "topic": msg.topic,
            "payload": payload
        }

    print(f"MQTT mensaje recibido: {msg.topic} -> {payload}")

    # --------------------------
    # POST hacia api_data.php
    # --------------------------
    try:
        r = requests.post(PHP_URL, data={
            "topic": msg.topic,
            "payload": payload
        })
        print("POST enviado a PHP, status:", r.status_code)
    except Exception as e:
        print("Error enviando POST a PHP:", e)


def message_to_json(msg_dict):
    out = {
        "topic": msg_dict.get("topic"),
        "received_at": datetime.now().isoformat()
    }
    payload = msg_dict.get("payload")

    if payload is None:
        out["payload"] = None
        out["payload_type"] = "none"
        return out

    try:
        parsed = json.loads(payload)
        out["payload"] = parsed
        out["payload_type"] = "json"
    except Exception:
        out["payload"] = payload
        out["payload_type"] = "text"
    return out

def publish_to_mqtt(message):
    client = mqtt.Client()
    client.connect(MQTT_HOST, MQTT_PORT, 60)
    client.publish("ac/act", message)
    client.disconnect()

@app.route("/toggle", methods=["POST"])
def toggle():
    value = request.json.get("value", "0")
    publish.single("ac/actuador", value, hostname="test.mosquitto.org", port=1883)
    return jsonify({"status": "OK", "sent": value})

if __name__ == '__main__':
    mqtt_client = mqtt.Client()
    mqtt_client.on_connect = on_connect
    mqtt_client.on_message = on_message

    mqtt_client.connect(MQTT_HOST, MQTT_PORT, 60)
    mqtt_client.loop_start()

    app.run(host='0.0.0.0', port=5000, debug=True, use_reloader=False)
