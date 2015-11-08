# Image Minify API

Install a Image-Compression-Service (like TinyPng, JPEGMini) on your own Server!

[![Build Status](https://travis-ci.org/ingowalther/image-minify-api.svg?branch=master)](https://travis-ci.org/ingowalther/image-minify-api)

- [Image Minify API](#)
	- [Installation](#installation)
		- [Clone Repository](#clone-repository)
		- [Install Dependencies (Composer)](#install-dependencies-composer)
		- [Set Database-Connection](#set-database-connection)
		- [Create Database Tables](#create-database-tables)
		- [Setup Webserver](#setup-webserver)
	- [Usage](#usage)
		- [Create API-Key](#create-api-key)
	    - [Compress an Image](#compress-an-image)
		    - [Response](#response)
		- [List all user](#list-all-user)    
    - [Clients](#api-clients)         
	- [TODO](#todo)	

Currently supports:
 - jpeg (mozJpeg, Installation Instructions: http://mozjpeg.codelove.de/binaries.html)
 - png (pngquant, https://pngquant.org/)

## Installation
### Clone Repository
```sh
git clone git@github.com:ingowalther/image-minify-api.git
```
### Install Dependencies (Composer)
```sh
composer install
```
### Set Database-Connection

Change  'config/services.yml':
```yaml
  connentionParams:
    url: mysql://root:root@localhost/image-minify-api
```
to your settings
### Create Database Tables
```sh
chmod a+x bin/console;
bin/console image-minify-api:setup
```

### Setup Webserver
```
vHost DocRoot -> web/
```

## Usage

### Create API-Key
```sh
 bin/console user:add
```
Enter a Username.
If the user is created correctly you will see the API-Key in your Terminal.

### Compress an Image

POST with Params "api_key" and File with Name "image" to http://yourserver/minify

Example:
```sh
curl --form "image=@test.jpg" --form api_key=VVDFNNflLIQdCH5vnx0RkmCxxjhHIL6  http://localhost/minify > test_2.jpg
```

#### Response
You will get a Json-Response like this:
```json
{
   "success":true,
   "oldSize":539,
   "newSize":394,
   "image":"\/9j\/4AAQSkZJRgABAQAAAQABAAD\/\/gATQ3JlYXRlZCB3aXRoIEdJTVD\/2wCEAAoKCgoKCgsMDAsPEA4QDxYUExMUFiIYGhgaGCIzICUgICUgMy03LCksNy1RQDg4QFFeT0pPXnFlZXGPiI+7u\/sBCgoKCgoKCwwMCw8QDhAPFhQTExQWIhgaGBoYIjMgJSAgJSAzLTcsKSw3LVFAODhAUV5PSk9ecWVlcY+Ij7u7+\/\/CABEIAAEAAQMBIgACEQEDEQH\/xAAUAAEAAAAAAAAAAAAAAAAAAAAH\/9oACAEBAAAAAGb\/xAAUAQEAAAAAAAAAAAAAAAAAAAAA\/9oACAECEAAAAH\/\/xAAUAQEAAAAAAAAAAAAAAAAAAAAA\/9oACAEDEAAAAH\/\/xAAUEAEAAAAAAAAAAAAAAAAAAAAA\/9oACAEBAAE\/AH\/\/xAAUEQEAAAAAAAAAAAAAAAAAAAAA\/9oACAECAQE\/AH\/\/xAAUEQEAAAAAAAAAAAAAAAAAAAAA\/9oACAEDAQE\/AH\/\/2Q=="
}
```
| Parameter  | Description |
| ------------- | ------------- |
| success | true or false  |
| oldSize  | ImageSize before compressing (in Byte)  |
| newSize  | ImageSize after compressing (in Byte)  |
| image  | The binarydata of the compressed image (base64 encoded)  |

### List all user
```sh
 bin/console user:list
```
Output:

![Console output](http://i.imgur.com/6SKcBcF.png)

## API-Clients

PHP: https://github.com/ingowalther/image-minify-php-client

## TODO
- Quota
- Setting quality over configuration
- Setting binary path configuration
- Logging
