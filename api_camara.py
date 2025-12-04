import paho.mqtt.client as mqtt
import json

# --- Configuración MQTT (Debe coincidir con la del Publicador) ---
MQTT_BROKER = "test.mosquitto.org"
MQTT_PORT = 1883
# Suscríbete al tópico que tu script de detección está usando
MQTT_TOPIC = "tomate/maduracion/carlos_sensor_1" 
CLIENT_ID = "TomateSubscriber_1"

# --- Funciones de Callback ---

def on_connect(client, userdata, flags, rc, properties=None):
    """Callback que se llama al establecer la conexión con el broker."""
    if rc == 0:
        print("✅ Conectado al broker MQTT exitosamente.")
        # Suscribirse al tópico tan pronto como se conecta
        client.subscribe(MQTT_TOPIC)
        print(f"📡 Suscrito al tópico: {MQTT_TOPIC}")
    else:
        print(f"❌ Falló la conexión con el broker. Código: {rc}")

def on_message(client, userdata, msg):
    """Callback que se llama cuando se recibe un mensaje del broker."""
    try:
        # Decodifica el payload (el mensaje) de bytes a string
        payload_str = msg.payload.decode()
        
        # Intenta parsear la cadena JSON a un diccionario de Python
        data = json.loads(payload_str)
        
        # Imprime los resultados de forma legible
        print("\n--- NUEVO RESULTADO ---")
        print(f"Tópico: {msg.topic}")
        print(f"Etapa de Maduración: *{data.get('etapa', 'N/A')}*")
        print(f"Verde: {data['porcentajes'].get('verde', 'N/A')}%")
        print(f"Amarillo: {data['porcentajes'].get('amarillo', 'N/A')}%")
        print(f"Rojo: {data['porcentajes'].get('rojo', 'N/A')}%")
        print(f"Área (px): {data.get('area_px', 'N/A')}")
        print(f"Timestamp: {data.get('timestamp', 'N/A')}")
        print("-----------------------")

    except json.JSONDecodeError:
        print(f"Mensaje recibido no es JSON válido: {msg.payload}")
    except Exception as e:
        print(f"Error al procesar el mensaje: {e}")


# --- Configuración y Bucle Principal ---

# Crear la instancia del cliente
client = mqtt.Client(mqtt.CallbackAPIVersion.VERSION1, CLIENT_ID)

# Asignar las funciones de callback
client.on_connect = on_connect
client.on_message = on_message

try:
    # Conectarse al broker
    client.connect(MQTT_BROKER, MQTT_PORT, 60)

    print("Esperando mensajes... (Presiona Ctrl+C para salir)")
    
    # Iniciar el bucle de red. Esto bloquea el código y escucha indefinidamente.
    client.loop_forever()

except KeyboardInterrupt:
    print("\nDesconectando...")
except Exception as e:
    print(f"Ocurrió un error: {e}")
    
finally:
    client.disconnect()