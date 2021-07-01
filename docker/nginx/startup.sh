#!/bin/bash

# if [ ! -f /etc/nginx/ssl/_CONTAINER_DOMAIN_.crt ]; then
#     openssl genrsa \
#     -out "/etc/nginx/ssl/_CONTAINER_DOMAIN_.key" 2048

#     openssl req -new -key "/etc/nginx/ssl/_CONTAINER_DOMAIN_.key" \
#     -out "/etc/nginx/ssl/_CONTAINER_DOMAIN_.csr" \
#     -subj "/CN=_CONTAINER_DOMAIN_/O=_CONTAINER_DOMAIN_/C=BR" \
#     -addext "subjectAltName=DNS:_CONTAINER_DOMAIN_" \
#     -addext "certificatePolicies=1.2.3.4"

#     openssl x509 -req -days 365 \
#     -in "/etc/nginx/ssl/_CONTAINER_DOMAIN_.csr" \
#     -signkey "/etc/nginx/ssl/_CONTAINER_DOMAIN_.key" \
#     -out "/etc/nginx/ssl/_CONTAINER_DOMAIN_.crt"
# fi

# Start crond in background
crond -l 2 -b

# Start nginx in foreground
nginx
