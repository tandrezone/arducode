

int led = 13 ;

#include <dht11.h>

DHT11 dht11(2);
void setup() 
{
	pinMode(LED_BUILTIN, OUTPUT);
	
	pinMode(led, OUTPUT);
	
	Serial.begin(9600);
}
void loop() 
{
	digitalWrite(LED_BUILTIN, HIGH); // turn the LED on (HIGH is the voltage level)
	delay(1000);                     // wait for a second
	digitalWrite(LED_BUILTIN, LOW);  // turn the LED off by making the voltage LOW
	delay(1000);                     // wait for a second
	
	digitalWrite(led, HIGH); // turn the LED on (HIGH is the voltage level)
	delay(1000);                      // wait for a second
	digitalWrite(led, LOW);  // turn the LED off by making the voltage LOW
	delay(1000);                      // wait for a second
	
	int temperature = 0;
	int humidity = 0;
	
	int result = dht11.readTemperatureHumidity(temperature, humidity);
	
	if (result == 0)
	{
		    Serial.print("Temperature: ");
		    Serial.print(temperature);
		    Serial.print(" °C\tHumidity: ");
		    Serial.print(humidity);
		    Serial.println(" %");
	}
	else
	{
		    Serial.println(DHT11::getErrorString(result));
	}
}
