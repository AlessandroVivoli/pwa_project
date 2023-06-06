## TVZ PWA College Project (Sopitas)

Before continuing modify the `${workspaceFolder}/src/.env.example` file and rename it to `.env`

### 1. Run apache and mysql server on xampp

### 2. Run the `mysql_upgrade` tool

#### Windows (PS Elevated Mode)

```ps
cd D:\xampp\mysql\bin
& .\mysql_upgrade.exe
```

#### Linux

```bash
cd /opt/lampp/bin
sudo mysql_upgrade
```

#### Mac

```bash
cd /Applications/XAMPP/xamppfiled/bin/
sudo mysql_upgrade
```

### 3. Generate mysql database and tables

Execute the `${workspaceFolder}/scripts/build_database.sql` script inside the [phpMyAdmin web panel](http://localhost/phpmyadmin/index.php?route=/server/sql)

### 4. Launch [URL](http://localhost/) in browser
