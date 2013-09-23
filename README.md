Cows-RESTful-API
================

A RESTful api used to interact with the UC Davis COmmunity Web Scheduler

This is intended to replace Cows-TV-Server, Cows-Tablet-Server and Cows-Mobile-Server with a single, more unified API.

##Authentication##

The API implements a version of an authentication scheme known as 2-legged OAuth. This involves both the client and the
server calculating a time dependent hash, and the server verifying the two hashes match.

At some point before execution, every client is assigned a public/private keypair. The public key is inconsequential,
but the private key must be kept secret, and after it is initially given to the client, must never be sent over the wire.

Users must use the authorization header to pass variables in the following format:

```php
$publicKey . "|" . $timeStamp . "|" . $signature;
```

The signature hash is calculated via

```php
hash_hmac('sha256',$requestMethod.$requestURI.$requestParamaters.time(),$privateKey)

$requestMethod: GET, POST, or DELETE
$requestURL: The URI accessed
$requestParameters: The parameters (except signature) used in the request.
time(): The current time on the client.
$privateKey: The client's private key.
```

This hash calculation is performed both by the client and the server.

The client passes the timestamp used, their public key, and their calculated signature to the server.

The server uses the public key to lookup the client's private key, and verifies the timestamp is within a reasonable 
window (we use +/- 5 minutes). The server then calculates the signature and matches it against the client's.

##Services Provided##
 All requests return a JSON object of the form
  ```json
  {
    "code" : "The status cde",
    "message" : "The response"
  }
  ```
  Code will be 0 in the case of a successful execution. Message will be blank or hold the result of a query or 
  location of the created resource depending on the exact API request
  
  As discussed in the Authentication section, all requests require time, publicKey, and signature present in 
  the Authorization header.
  
  "Authentication?" refers to the need to run POST /session/:siteid before your query and DELETE /session afterwards

  GET /
  
      Paramaters: format(only json)
      Returns: API documentation
      Authentication?: No
      Description: Outputs API documentation in the specified format (currently json or plain (by default)).
  
  GET /error
  
      Paramaters: None
      Returns: Error documentation
      Authentication?: No
      Description: Returns a mapping of error codes to the error type in json format.
  
  POST /session/:siteId
  
      Parameters: tgc - Ticket Granting Cookie from CAS (required)
      Returns: Nothing
      Description: Associates your public key with a cows session for the specified site.
  DELETE /session/:siteId
  
      Required Parameters: None
      Returns: Nothing
      Description: Logs you out from COWS. Deletes all session cookies. Disassociates your public key with a cows session.
        
  GET /event
  
      Paramaters: timeStart and timeEnd (if filtering by time is desired), 
          Filter paramaters used by COWS rss feeds (see below)
      Returns: Event Information
      Authentication?: If site is not in "anonymous mode"
      Description: Returns a json object representing all events that fit the given filters
  POST /event
  
      Paramaters: Cows Event Paramaters (see below)
      Returns: Created Event ID
      Authentication?: Yes
      Description: Creates an event with the specified parameters
      
  GET /event/:id
  
      Paramaters: None
      Returns: Event Information
      Authentication?: If site is not in "anonymous mode"
      Description: Returns a json object representing the event with the specified ID
  DELETE /event/:id
  
      Paramaters: None  
      Returns: Nothing
      Authentication?: Yes
      Description: Deletes the event with the specified id

Cows Event Parameters:

    Required Parameters:
    'EventTitle'
    'StartDate'
    'EndDate'
    'StartTime'
    'EndTime'
    'DisplayStartTime'
    'DisplayEndTime' 
    'BuildingAndRoom'
    'Categories'
    'ContactPhone'
    'EventTypeName'
    (To be continued)

Known Cows RSS Filters:
    
    BuilingAndRoom
    DisplayLocations
    (To be continued)
  
