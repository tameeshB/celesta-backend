# celesta-backend
## API details:  
### Compousloury API Key, send via post as 'apiKey'
/login   
* POST  
  *  emailid   
  *  password   
* JSON Response:  
{   
  status: //http status code   
  userID: //uid of logged in user in case of successful login    
  name : //name   
  college : //college   
  events: ['event1',event2],   
  message: //message from the server    
}   
  
/register
* POST  
  *  name   
  *  emailid   
  *  password   
  *  mobile
  *  college
* JSON Response:  
{   
  status: //http status code   
  message: //message from the server    
}   
Status code:  
  * 200 : successful   
  * 500 : DB connect error   
  * 409 : Duplicate entry for registration
  * 403 : unauthorised/invalid login   
  * 400 : bad request error   
  * description of error is in the "message of the JSOn object"  
  
