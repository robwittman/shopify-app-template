#!/usr/bin/env bash

DOMAIN=$1

if [ -z "$DOMAIN" ]; then
  echo -n 'Enter root domain (no www): '
  read input_d
  DOMAIN=$input_d
fi

[ -d data/certs ] || mkdir data/certs

# Easiest to generate conf file for each
# certificate creation process
OpenSSLConf="$DOMAIN"-openssl.cnf

cat >"$OpenSSLConf" <<EOL
[req]
req_extensions = v3_req
distinguished_name = req_distinguished_name
[ req_distinguished_name ]
countryName                 = Country
countryName_default         = US
stateOrProvinceName         = State
stateOrProvinceName_default = OR
localityName                = City
localityName_default        = Portland
commonName                  = Common Name
commonName_default          = *.$DOMAIN
[ v3_req ]
basicConstraints = CA:FALSE
keyUsage = nonRepudiation, digitalSignature, keyEncipherment
subjectAltName = @alt_names
[alt_names]
DNS.1 = $DOMAIN
DNS.2 = *.$DOMAIN
EOL

# Create Private RSA Key
openssl genrsa -out "data/certs/$DOMAIN".key 1024

# Create Certifcate Signing Request
openssl req -new -key "data/certs/$DOMAIN".key -out "data/certs/$DOMAIN".csr -config "$OpenSSLConf"

# Create Certifcate
openssl x509 -req -days 365 -in "data/certs/$DOMAIN".csr \
-signkey "data/certs/$DOMAIN".key -out "data/certs/$DOMAIN".crt \
-extensions v3_req \
-extfile "$OpenSSLConf"

# Nix the configfile
rm -- "$OpenSSLConf"
