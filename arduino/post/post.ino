#include<SPI.h>
#include<Ethernet.h>

byte mac[] = {
0xDE, 0xAD, 0xBE, 0xEF, 0xFE, 0xED };
IPAddress ip(192, 168, 1, 175);

IPAddress serv(192,168,1,15);

#define SS_PIN_RFID 49 
#define SS_PIN_ETHERNET 53

bool alreadyConnected = false;
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
        Serial.print(c);
      
        
       }
       s2= s.substring(s.length()-1,s.length());
       Serial.println(s2);
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
        Serial.print(c);
      
        
       }
       s2= s.substring(s.length()-1,s.length());
       Serial.println(s2);
    }       
        
     else {
      s2="4";
  // if you didn't get a connection to the server:
  Serial.println("connection failed");
  }
  return s2;
}

void ulaz_izlaz(String uid_kartice)
{
  digitalWrite(SS_PIN_RFID,HIGH);
  digitalWrite(SS_PIN_ETHERNET,LOW);
  

  if(client.connect(serv,80))
  {
    String ispis="uid_kartice=" + uid_kartice;
    Serial.println("connected");
    client.println("POST /data/ulaz_izlaz.php HTTP/1.1");
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