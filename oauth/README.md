# Brisbane OAuth Server

## Setup server
- Run
```
 sf league:oauth2-server:create-client brisbane-app --redirect-uri=http://localhost:1500/oauth-validate-code --grant-type=authorization_code --scope=user.info
```
to configure the server