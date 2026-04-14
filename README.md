# WOW WEBSITE

```
composer require pusher/pusher-php-server
```

calling tailwind:

```
npx @tailwindcss/cli -i ./assets/css/input.css -o ./assets/css/output.css --watch
```

## Uploading files:

Give permission to upload folder: 
```
chmod 0755 uploads && sudo chown -R daemon:daemon uploads
```

Push to production:

```
zip -r ../wow_website.zip . -x "uploads/*" -x "*.DS_Store" -x "README.md" -x ".gitignore" -x ".git/*"
```
