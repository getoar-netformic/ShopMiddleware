## Run migration
To run the application You can call defin command on cli or access it from http requests

### Run this:
To start migration: 
```bash
 php bin/console.php migrate:documents
```

To enhance documents data:
```bash
php bin/console.php migrate:enhance-migrated-documents
```

### Run this from http:
-  Open `http://localhost:8080/migrate-order-documents`.

