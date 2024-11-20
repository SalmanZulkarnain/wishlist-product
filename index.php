<?php
require 'config.php';
require 'functions.php';

$pesan = '';
$product_edit = null;

$products = viewProduct();
deleteProduct();

if (isset($_GET['edit'])) {
    $product_edit = ambilProduct();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'update':
                updateProduct();
                break;
            case 'tambah':
                $pesan = insertProduct();
                break;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>WISHLIST PRODUK</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="style/style.css">
</head>

<body>
    <div class="container">
        <div class="form-container">
            <form method="post" enctype="multipart/form-data">
                <input type="hidden" name="action" value="<?php echo $product_edit ? 'update' : 'tambah'; ?>">
                <input type="hidden" name="id" value="<?php echo $product_edit ? $product_edit['id'] : ''; ?>">
                <div class="input-group">
                    <input type="text" name="nama_barang" placeholder="Masukkan nama barang"
                        value="<?php echo $product_edit ? $product_edit['nama_barang'] : ''; ?>" />
                </div>
                <div class="input-group">
                    <input type="text" name="url_barang" placeholder="Masukkan url barang"
                        value="<?php echo $product_edit ? $product_edit['url_barang'] : ''; ?>" />
                </div>
                <div class="input-group">
                    <select name="status">
                        <option value="penting" <?php echo ($product_edit && $product_edit['status'] == 'penting') ? 'selected' : ''; ?>>Penting</option>
                        <option value="tidak_penting" <?php echo ($product_edit && $product_edit['status'] == 'tidak_penting') ? 'selected' : ''; ?>>Tidak Penting</option>
                    </select>
                </div>
                <div class="input-group">
                    <input type="file" name="gambar_barang" />
                    <?php if ($product_edit && $product_edit['gambar_barang']): ?>
                        <p>Gambar saat ini: <img src="<?php echo $product_edit['gambar_barang']; ?>" width="100" /></p>
                    <?php endif; ?>
                </div>
                <div class="input-group">
                    <input type="submit" name="submit" value="<?php echo $product_edit ? 'Update' : 'Tambah' ?>">
                </div>
            </form>
        </div>

        <div class="card-container">
            <?php foreach ($products as $key => $product) { ?>
                <div class="card">
                    <div class="card-header">
                        <img src="<?php echo $product['gambar_barang']; ?>" width="250px" height="300px" />
                    </div>
                    <div class="card-content-footer">
                        <div class="card-content">
                            <h4><?php echo $product['nama_barang']; ?></h4>
                        </div>
                        <div class="card-footer">
                            <a href="<?php echo $product['url_barang']; ?>">Klik link disini</a>
                            <a href="index.php?edit=<?php echo $product['id']; ?>"><i class="fa-solid fa-pen-to-square"></i></a>
                            <a href="index.php?delete=<?php echo $product['id']; ?>"
                                onclick="return confirm('Apakah anda yakin ingin menghapus?')"><i class="fa-solid fa-trash"></i></a>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</body>

</html>