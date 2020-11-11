# Photography

## Configuration

#### PHP Configuration

Make sure the following configurations are set:

`php.ini` (On Mac - /usr/local/etc/php/7.3/php.ini)
```ini
upload_max_filesize = 256M
memory_limit = 512M
post_max_size = 256M
```

`php-memory-limits.ini` (On Mac - /usr/local/etc/php/7.3/conf.d/php-memory-limits.ini)
```ini
upload_max_filesize = 256M
memory_limit = 512M
post_max_size = 256M
```

`nginx.conf` (On Mac - /usr/local/etc/nginx/nginx.conf)
```apacheconfig
client_max_body_size 256M;
```

`valet.conf` (On Mac - /usr/local/etc/nginx/valet/valet.conf)
```apacheconfig
client_max_body_size 256M;
```

#### Google API Key (Maps, etc.)

You'll need a Google API Key if you plan to use any of the Google Cloud Platform (GCP) services, such as geolocation, etc.

In the [Google Cloud Platform](https://console.cloud.google.com/projectselector2/home/dashboard), select (or create) your project, then go to APIs, and then Credentials. Add an API Key here and then go to the Library tab. From here, find the Geocoding API and enable it for your API Key. Then set the API Key in the .env:
```ini
GOOGLECLOUDPLATFORM_API_KEY=<your-api-key>
```

#### Google Drive OAuth Client Keys

In the [Google API Console](https://console.developers.google.com/apis/dashboard), you'll need to setup an OAuth consent screen and a Credential.

The Client ID Credential that you setup needs to be scoped for `Google_Service_Drive::DRIVE` (`https://www.googleapis.com/auth/drive`).

Once you've created the credential / access token, save them and add them to the .env:
```ini
GOOGLEDRIVE_CLIENT_ID=<your-client-id>
GOOGLEDRIVE_CLIENT_SECRET=<your-client-secret>
```

#### Flickr API Key

You'll also need to create [Flickr API Keys](https://www.flickr.com/services/api/keys/). Once you've created your keys, set them in the .env:
```ini
FLICKR_CONSUMER_KEY=<your-api-key>
FLICKR_CONSUMER_SECRET_KEY=<your-api-secret>
```


---



## Installation

#### ...


---


## Commands

#### Create User
`php artisan create-user`
This command allows you to manually create a user in the database. You will be prompted for the email, password, and name of the user to be created. Email addresses must be unique among users.


---
