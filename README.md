Pengaturan Laravel dengan Docker
================================

Langkah 1: Jalankan Aplikasi
-------------------------------
    
    docker-compose up -d
    

Langkah 2: Impor DB
------------------------------------

Salin File SQL ke dalam Container:

    docker cp sistem_gudang.sql sistem-gudang-db:/sistem_gudang.sql
    

Impor Database di Dalam Container:

    docker exec -it sistem-gudang-db bash
    mysql -u root -p sistem_gudang < /sistem_gudang.sql
    



Akses aplikasi : http://127.0.0.1:8000

Link Dokumentasi : https://documenter.getpostman.com/view/24346209/2sAXjNXWDh