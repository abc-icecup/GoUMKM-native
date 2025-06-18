<style>
    .footer {
        background-color: #205781;
        padding: 30px 20px 10px;
        color: white;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        margin-top: 60px;
        box-shadow: 0 -2px 6px rgba(0, 0, 0, 0.05);
    }

    .footer-container {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        align-items: flex-start;
        max-width: 1200px;
        margin: auto;
    }

    .footer-left {
        flex: 1 1 300px;
        margin-bottom: 20px;
    }

    .footer-left h4 {
        margin-bottom: 10px;
        font-size: 20px;
    }

    .footer-right {
        flex: 1 1 200px;
        text-align: right;
    }

    .footer-right ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .footer-right li {
        margin-bottom: 8px;
    }

    .footer-right a {
        text-decoration: none;
        color: white;
        font-weight: 500;
        transition: color 0.2s;
    }

    .footer-right a:hover {
        color: #007bff;
    }

    .footer-bottom {
        text-align: center;
        font-size: 14px;
        padding-top: 15px;
        border-top: 1px solid #dee2e6;
        margin-top: 20px;
        color: whitesmoke;
    }
</style>

<footer class="footer">
  <div class="footer-container">
    <div class="footer-left">
      <h4>GoUMKM</h4>
      <p>Mendukung pertumbuhan UMKM Indonesia melalui digitalisasi dan semangat kolaborasi.</p>
    </div>
    <div class="footer-right">
      <ul>
        <li><a href="beranda.php">Beranda</a></li>
        <li><a href="tentang.php">Tentang Kami</a></li>
        <li><a href="kontak.php">Kontak</a></li>
        <li><a href="kebijakan.php">Kebijakan Privasi</a></li>
      </ul>
    </div>
  </div>
  <div class="footer-bottom">
    <p>&copy; <?= date("Y") ?> GoUMKM. All rights reserved.</p>
  </div>
</footer>