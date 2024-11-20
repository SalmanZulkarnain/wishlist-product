<?php
if (!DEFINED('WISHLIST_APP')) {
    DEFINE('WISHLIST_APP', 'db_wishlist.sqlite');
}

$db = new SQLite3(WISHLIST_APP);

if (!$db) {
    echo $db->lastErrorMsg();
}

$db->query("CREATE TABLE IF NOT EXISTS wishlist (
    id INTEGER PRIMARY KEY,
    nama_barang TEXT NOT NULL,
    url_barang TEXT NOT NULL,
    gambar_barang TEXT NOT NULL,
    status TEXT CHECK (status IN ('penting', 'tidak_penting'))
)");
