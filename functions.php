<?php

require 'config.php';

function insertProduct() {
    global $db;
    
    $pesan = '';
    if (isset($_POST['submit'])) {
        $nama_barang = $_POST['nama_barang'];
        $url_barang = $_POST['url_barang'];
        $status = $_POST['status'];

        // Handle upload gambar
        $gambar_barang = '';
        if (isset($_FILES['gambar_barang']) && $_FILES['gambar_barang']['error'] == 0) {
            $target_dir = "uploads/";
            $target_file = $target_dir . basename($_FILES["gambar_barang"]["name"]);

            $check = getimagesize($_FILES["gambar_barang"]["tmp_name"]);
            if ($check !== false) {
                if (move_uploaded_file($_FILES["gambar_barang"]["tmp_name"], $target_file)) {
                    $gambar_barang = $target_file;
                } else {
                    $pesan = "Gagal mengunggah gambar.";
                    return $pesan;
                }
            } else {
                $pesan = "File bukan gambar.";
                return $pesan;
            }
        }
        // Insert data ke database
        $stmt = $db->prepare("INSERT INTO wishlist (nama_barang, url_barang, status, gambar_barang) VALUES (:nama_barang, :url_barang, :status, :gambar_barang)");
        $stmt->bindParam(':nama_barang', $nama_barang, SQLITE3_TEXT);
        $stmt->bindParam(':url_barang', $url_barang, SQLITE3_TEXT);
        $stmt->bindParam(':status', $status, SQLITE3_TEXT);
        $stmt->bindParam(':gambar_barang', $gambar_barang, SQLITE3_TEXT);

        if ($stmt->execute()) {
            header('Location: index.php');
            exit;
        } else {
            $pesan = "Gagal menambahkan produk.";
        }
    }
    return $pesan;
}

function viewProduct()
{
    global $db;

    $result = $db->query("SELECT * FROM wishlist ORDER BY status = 'penting' DESC, id ASC");
    $data = [];
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $data[] = $row;
    }

    return $data;
}

function ambilProduct()
{
    global $db;

    if (!isset($_GET['edit'])) {
        return null;
    }

    $id = $_GET['edit'];
    $stmt = $db->prepare("SELECT * FROM wishlist WHERE id = :id");
    $stmt->bindParam(':id', $id, SQLITE3_INTEGER);
    $ambil = $stmt->execute();

    return $ambil->fetchArray(SQLITE3_ASSOC);
}

function updateProduct() {
    global $db;

    $pesan = '';
    if (isset($_POST['submit'])) {
        $id = $_POST['id'];
        $nama_barang = $_POST['nama_barang'];
        $url_barang = $_POST['url_barang'];
        $status = $_POST['status'];

        // Ambil gambar lama dari database
        $current_product = $db->querySingle("SELECT gambar_barang FROM wishlist WHERE id = :id", true);
        $gambar_barang = $current_product['gambar_barang'];

        // Cek apakah ada file gambar baru diupload
        if (isset($_FILES['gambar_barang']) && $_FILES['gambar_barang']['error'] == 0) {
            $target_dir = "uploads/";
            $target_file = $target_dir . basename($_FILES["gambar_barang"]["name"]);

            $check = getimagesize($_FILES["gambar_barang"]["tmp_name"]);
            if ($check !== false) {
                if (move_uploaded_file($_FILES["gambar_barang"]["tmp_name"], $target_file)) {
                    // Hapus gambar lama jika ada
                    if (file_exists($gambar_barang)) {
                        unlink($gambar_barang);
                    }
                    $gambar_barang = $target_file; // Update dengan gambar baru
                } else {
                    $pesan = "Gagal mengunggah gambar.";
                    return $pesan;
                }
            } else {
                $pesan = "File bukan gambar.";
                return $pesan;
            }
        } else {
            // Jika tidak ada gambar baru, gunakan gambar lama
            $gambar_barang = $current_product['gambar_barang'];
        }

        // Update data produk
        $stmt = $db->prepare("UPDATE wishlist SET nama_barang = :nama_barang, url_barang = :url_barang, status = :status, gambar_barang = :gambar_barang WHERE id = :id");
        $stmt->bindParam(':id', $id, SQLITE3_INTEGER);
        $stmt->bindParam(':nama_barang', $nama_barang, SQLITE3_TEXT);
        $stmt->bindParam(':url_barang', $url_barang, SQLITE3_TEXT);
        $stmt->bindParam(':status', $status, SQLITE3_TEXT);
        $stmt->bindParam(':gambar_barang', $gambar_barang, SQLITE3_TEXT);

        if ($stmt->execute()) {
            header('Location: index.php');
            exit;
        } else {
            $pesan = "Gagal memperbarui produk.";
        }
    }
    return $pesan;
}




function deleteProduct()
{
    global $db;

    if (isset($_GET['delete'])) {
        $id = $_GET['delete'];

        $stmt = $db->prepare("DELETE FROM wishlist WHERE id = :id");
        $stmt->bindParam(':id', $id, SQLITE3_INTEGER);

        if ($stmt->execute()) {
            header('Location: index.php');
            exit;
        }
    }
}
