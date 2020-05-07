

#include<SPI.h>
#include<Ethernet.h>
#include <MFRC522.h>
#include <Keypad.h>
#include <LiquidCrystal_I2C.h>


int retry_count=0;
//inicilizacija postavki ethernet shielda
byte mac[] = {
  0xDE, 0xAD, 0xBE, 0xEF, 0xFE, 0xED };
IPAddress ip(192, 168, 1, 175);

IPAddress serv(192,168,1,11);
#define RST_PIN_RFID 5 
#define SS_PIN_RFID 49 
#define SS_PIN_ETHERNET 53
#define PIEZO_PIN 40
EthernetClient client; 
bool alreadyConnected = false;
MFRC522 mfrc522(SS_PIN_RFID, RST_PIN_RFID);  // Create MFRC522 instance
boolean RFIDMode=true;
String getID(){
  String content="";
  for (byte i = 0; i < mfrc522.uid.size; i++) 
  {
     if(i==0)
     {
      content.concat(String(mfrc522.uid.uidByte[i]<0x10 ? " 0" : " " )); 
      content.concat(String(mfrc522.uid.uidByte[i], HEX));
     }
     else
     {
     content.concat(String(mfrc522.uid.uidByte[i] < 0x10 ? " 0" : "%20"));
     content.concat(String(mfrc522.uid.uidByte[i], HEX));
     }
  }
  
  content.toUpperCase();
  return content.substring(1);
}


#define Password_Length 5
char keyPass[4];
char Data[Password_Length]; 
byte data_count = 0;
char key_pressed;
int i=0;
const byte ROWS = 4;
const byte COLS = 4;
bool enter_pass=false;

char hexaKeys[ROWS][COLS] = {
  {'1', '2', '3', 'A'},
  {'4', '5', '6', 'B'},
  {'7', '8', '9', 'C'},
  {'*', '0', '#', 'D'}
};

byte rowPins[ROWS] = {31,33,35,37};
byte colPins[COLS] = {30,32,34,36};

Keypad customKeypad = Keypad(makeKeymap(hexaKeys), rowPins, colPins, ROWS, COLS);
LiquidCrystal_I2C lcd(0x27, 16, 2);  




String check_card(String uid_kartice)
{
    digitalWrite(SS_PIN_RFID,HIGH);
     digitalWrite(SS_PIN_ETHERNET,LOW);
    String s2;
    
    if(client.connect(serv,80))
    {
        String ispis = "uid_kartice=" + uid_kartice;
        Serial.println("connected");
        client.println("POST /data/check_card.php HTTP/1.1");
        client.print("Host: ");
        client.println(serv);
        client.println("Content-Type: application/x-www-form-urlencoded");
        client.print("Content-Length: ");
        client.println(ispis.length());
        client.println();
        client.print(ispis);
        
      
        delay(500);
        String s;
       while(client.available())
       {
        char c = client.read();
        s += c;
        
      
        
       }
       s2= s.substring(s.length()-1,s.length());
       
    }
     else {
      s2="4";
// if you didn't get a connection to the server:
Serial.println("connection failed");
}

return s2;
}

void block_card(String uid_kartice)
{
    digitalWrite(SS_PIN_RFID,HIGH);
    digitalWrite(SS_PIN_ETHERNET,LOW);
    
    
    if(client.connect(serv,80))
    {
        String ispis = "uid_kartice=" + uid_kartice;
        Serial.println("connected");
        client.println("POST /data/block_card.php HTTP/1.1");
        client.print("Host: ");
        client.println(serv);
        client.println("Content-Type: application/x-www-form-urlencoded");
        client.print("Content-Length: ");
        client.println(ispis.length());
        client.println();
        client.print(ispis);
      
        delay(500);
       
    }
     else {
     
// if you didn't get a connection to the server:
Serial.println("connection failed");
}

}

String password_verify(String uid_kartice,char * password)
{
    digitalWrite(SS_PIN_RFID,HIGH);
    digitalWrite(SS_PIN_ETHERNET,LOW);
    String pass_temp(password);
    String pass= pass_temp.substring(0,pass_temp.length()-1);
    String s2;
  
 
    if(client.connect(serv,80))
    {
        String ispis = "uid_kartice=" + uid_kartice;
        String ispis2 = "password=";
        int i = ispis.length()+1+ispis2.length()+4;
        Serial.println("connected");
        client.println("POST /data/password_verify.php HTTP/1.1");
        client.print("Host: ");
        client.println(serv);
        client.println("Content-Type: application/x-www-form-urlencoded");
        client.print("Content-Length: ");
        client.println(i);
        client.println();
        client.print(ispis);
        client.print("&");
        client.print(ispis2);
        client.print(pass);
        delay(500);
          String s;
       while(client.available())
       {
        char c = client.read();
        s += c;
        
      
        
       }
       s2= s.substring(s.length()-1,s.length());
       
    }       
        
     else {
      s2="4";
  // if you didn't get a connection to the server:
  Serial.println("connection failed");
  }
  return s2;
}

void ulaz(String uid_kartice)
{
  String s2;
  String s;
  digitalWrite(SS_PIN_RFID,HIGH);
  digitalWrite(SS_PIN_ETHERNET,LOW);
  

  if(client.connect(serv,80))
  {
    String ispis="uid_kartice=" + uid_kartice;
    Serial.println("connected");
    client.println("POST /data/ulaz.php HTTP/1.1");
    client.print("Host: ");
    client.println(serv);
    client.println("Content-Type: application/x-www-form-urlencoded");
    client.print("Content-Length: ");
    client.println(ispis.length());
    client.println();
    client.print(ispis);
    delay(500);
     while(client.available())
       {
        char c = client.read();
        s += c;
        
      
        
       }
       s2= s.substring(s.length()-1,s.length());
       

  }
  else {
     
  // if you didn't get a connection to the server:
  Serial.println("connection failed");
  }
 
}
String izlaz_check(String uid_kartice)
{
  digitalWrite(SS_PIN_RFID,HIGH);
  digitalWrite(SS_PIN_ETHERNET,LOW);
  String s2;

  if(client.connect(serv,80))
  {
    String ispis="uid_kartice=" + uid_kartice;
    Serial.println("connected");
    client.println("POST /data/izlaz_check.php HTTP/1.1");
    client.print("Host: ");
    client.println(serv);
    client.println("Content-Type: application/x-www-form-urlencoded");
    client.print("Content-Length: ");
    client.println(ispis.length());
    client.println();
    client.print(ispis);
    delay(500);
    String s;
    while(client.available())
    {
      char c = client.read();
      s += c;
      Serial.print(c);
    }
    s2= s.substring(s.length()-1,s.length());
    
  }
  else {
      s2="4";
  // if you didn't get a connection to the server:
  Serial.println("connection failed");
  }
  return s2;
}
void wrong_pass(String uid_kartice)
{
  digitalWrite(SS_PIN_RFID,HIGH);
  digitalWrite(SS_PIN_ETHERNET,LOW);
  

  if(client.connect(serv,80))
  {
    String ispis="uid_kartice=" + uid_kartice;
    Serial.println("connected");
    client.println("POST /data/wrong_pass.php HTTP/1.1");
    client.print("Host: ");
    client.println(serv);
    client.println("Content-Type: application/x-www-form-urlencoded");
    client.print("Content-Length: ");
    client.println(ispis.length());
    client.println();
    client.print(ispis);
    delay(500);

    
  }
  else {
      
  // if you didn't get a connection to the server:
  Serial.println("connection failed");
  }
  
}
void send_block_card(String uid_kartice)
{
  digitalWrite(SS_PIN_RFID,HIGH);
  digitalWrite(SS_PIN_ETHERNET,LOW);
 

  if(client.connect(serv,80))
  {
    String ispis="uid_kartice=" + uid_kartice;
    Serial.println("connected");
    client.println("POST /data/send_block_card.php HTTP/1.1");
    client.print("Host: ");
    client.println(serv);
    client.println("Content-Type: application/x-www-form-urlencoded");
    client.print("Content-Length: ");
    client.println(ispis.length());
    client.println();
    client.print(ispis);
    delay(500);
  
    
  }
  else {
    
  // if you didn't get a connection to the server:
  Serial.println("connection failed");
  }
 
}
void unknown_card(String uid_kartice)
{
  digitalWrite(SS_PIN_RFID,HIGH);
  digitalWrite(SS_PIN_ETHERNET,LOW);


  if(client.connect(serv,80))
  {
    String ispis="uid_kartice=" + uid_kartice;
    Serial.println("connected");
    client.println("POST /data/unknown_card.php HTTP/1.1");
    client.print("Host: ");
    client.println(serv);
    client.println("Content-Type: application/x-www-form-urlencoded");
    client.print("Content-Length: ");
    client.println(ispis.length());
    client.println();
    client.print(ispis);
    delay(500);
  }
  else {
   
  // if you didn't get a connection to the server:
  Serial.println("connection failed");
  }
  
}


void setup()
{
     pinMode(PIEZO_PIN,OUTPUT);
    lcd.init();
    lcd.backlight();
   
    Serial.begin(9600);
    digitalWrite(SS_PIN_RFID,HIGH);
    digitalWrite(SS_PIN_ETHERNET,LOW);
    Ethernet.begin(mac, ip);
   
    // Check for Ethernet hardware present
  if (Ethernet.hardwareStatus() == EthernetNoHardware) {
    Serial.println("Ethernet shield was not found.  Sorry, can't run without hardware. :(");
    while (true) {
      delay(1); // do nothing, no point running without Ethernet hardware
    }
  }
  if (Ethernet.linkStatus() == LinkOFF) {
    Serial.println("Ethernet cable is not connected.");
  }


  digitalWrite(SS_PIN_ETHERNET,HIGH);
  digitalWrite(SS_PIN_RFID,LOW);
  
  mfrc522.PCD_Init();    // Init MFRC522
  delay(4);       // Optional delay. Some board do need more time after init to be ready, see Readme
  mfrc522.PCD_DumpVersionToSerial();  // Show details of PCD - MFRC522 Card Reader details
  Serial.println(F("Scan PICC to see UID, SAK, type, and data blocks..."));

}


bool millis_definied = false;
unsigned long startMillis;
unsigned long currentMillis;


void loop()
{

  
 digitalWrite(SS_PIN_ETHERNET,HIGH);
 digitalWrite(SS_PIN_RFID,LOW);
 
  if(RFIDMode==true)
  {
    lcd.setCursor(0, 0);
    lcd.print("Skenirajte vasu");
    lcd.setCursor(0, 1);
    lcd.print("    karticu");
     if ( ! mfrc522.PICC_IsNewCardPresent()) {
        return;
     }
     if ( ! mfrc522.PICC_ReadCardSerial()) {
    return;
      }
    String kod = getID();
   
    String check= check_card(kod);
    
   
    if(check=="0")
    {

        lcd.clear();
        lcd.setCursor(0, 0);
        lcd.print("    Nepoznata");
        lcd.setCursor(0, 1);
        lcd.print("    kartica");
        delay(800);
        lcd.clear();
        lcd.setCursor(0,0);
        lcd.print("    Pristup");
        lcd.setCursor(0,1);
        lcd.print("  nedozvoljen");
        unknown_card(kod);
        tone(PIEZO_PIN,2000,1500);
        delay(3000);
        lcd.clear();
    }
    else if(check=="1")
    {
      
     
      String provjera = izlaz_check(kod);
      if(provjera=="1")
      {
        lcd.clear();
        lcd.print("    Dovidenja");
        
        tone(PIEZO_PIN,1300,500);
      delay(2000);
        
      }
      else
      {
       lcd.clear();
        
      lcd.print("     Kartica");
      lcd.setCursor(0,1);
      lcd.print( "   prepoznata");
      
      
      tone(PIEZO_PIN,1300,500);
      delay(2000);  
      lcd.clear();
      lcd.print("Unesite lozinku");
      lcd.setCursor(0,1);
      
      RFIDMode = false; // Make RFID mode false
      }
     
    }
    else if (check=="2")
    {
      lcd.clear();
        lcd.setCursor(0, 0);
        lcd.print("    Blokirana");
        lcd.setCursor(0, 1);
        lcd.print("    kartica");
        send_block_card(kod);
        delay(800);
        lcd.clear();
        lcd.setCursor(0,0);
        lcd.print("    Pristup");
        lcd.setCursor(0,1);
        lcd.print("  nedozvoljen");
        tone(PIEZO_PIN,2000,1500);
        delay(3000);
        lcd.clear();
    }
    else if (check=="4")
    {
      lcd.clear();
        lcd.setCursor(0, 0);
        lcd.print("    Ne mogu se");
        lcd.setCursor(0, 1);
        lcd.print("    povezati");
        delay(800);
        lcd.clear();
        lcd.setCursor(0,0);
        lcd.print("    Pristup");
        lcd.setCursor(0,1);
        lcd.print("  nedozvoljen");
        tone(PIEZO_PIN,2000,1500);
        delay(3000);
        lcd.clear();
    }
  }
  
  String kod = getID();
  if(RFIDMode==false)
  {
    
     
    if(retry_count<3)
    {
      
    if(enter_pass==true)
      {
        lcd.clear();
        lcd.setCursor(0,0);
        lcd.print("Unesite lozinku");
        lcd.setCursor(0,1);
        enter_pass=false;
      }
      if(millis_definied==false)
      {
      startMillis = millis();
      millis_definied=true;
      
      }
    key_pressed= customKeypad.getKey();
    if(key_pressed)
    {
      
      keyPass[i++]=key_pressed;
      lcd.print("*");
      millis_definied=false;
      }
    else
    {
      
      currentMillis=millis();
      
      Serial.println(currentMillis);
      if(currentMillis-startMillis>= 5000)
      {
        retry_count=0;
        millis_definied=false;
        RFIDMode=true;
        
      }
    }
     if(i==4)
     {
      millis_definied=false;
      delay(200);
      String pass_check = password_verify(kod,keyPass);
      if(pass_check=="1")
      {
        lcd.clear();
        lcd.print("Lozinka tocna");
        delay(900);
        lcd.clear();
        lcd.print("    Pristup");
        lcd.setCursor(0,1);
        lcd.print("   dozvoljen");
        
        ulaz(kod);
        tone(PIEZO_PIN,1300,500);
        delay(3000);
        lcd.clear();
        i=0;
        retry_count=0;
        enter_pass=true;
        RFIDMode=true;
      }
      else if (pass_check==4)
     {
       lcd.clear();
        lcd.setCursor(0, 0);
        lcd.print("    Ne mogu se");
        lcd.setCursor(0, 1);
        lcd.print("    povezati");
        delay(800);
        lcd.clear();
        lcd.setCursor(0,0);
        lcd.print("    Pristup");
        lcd.setCursor(0,1);
        lcd.print("  nedozvoljen");
        tone(PIEZO_PIN,2000,1500);
        delay(3000);
        lcd.clear();
        i=0;
        retry_count=0;
        RFIDMode=true;
     }
      else 
      {
        
        lcd.clear();
        lcd.print("Netocna lozinka");
        wrong_pass(kod);
        tone(PIEZO_PIN,2000,1000);
        delay(1000);
        lcd.clear();
        i=0;
        
        retry_count++;
       
      }
      enter_pass=true;
     }
     
    }
    else
    {
       i=0;
        block_card(kod);
        lcd.clear();
        lcd.setCursor(0, 0);
        lcd.print("    Blokirana");
        lcd.setCursor(0, 1);
        lcd.print("    kartica");
        send_block_card(kod);
        delay(800);
        lcd.clear();
        lcd.setCursor(0,0);
        lcd.print("    Pristup");
        lcd.setCursor(0,1);
        lcd.print("  nedozvoljen");
        tone(PIEZO_PIN,2000,1500);
        delay(3000);
        lcd.clear();
        retry_count=0;
       RFIDMode = true;
    }
   
  }
  }
