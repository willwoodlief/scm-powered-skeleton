######
##  These can run relative to the install root directory
##   Exposes these to the world on the web since the document root is the public directory
#####

#uploads for uploading images and docs
if [[ ! -e storage/app/uploads ]]; then
    mkdir -p storage/app/uploads
fi

chmod 755 storage/app/uploads

if [[ ! -e public/uploads ]]; then
    ln -s storage/app/uploads public
fi

