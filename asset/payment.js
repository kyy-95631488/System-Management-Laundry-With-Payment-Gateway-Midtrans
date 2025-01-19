// For example trigger on button clicked, or any time you need
const payButton = document.getElementById("pay-button");
const form = document.getElementById("payment-form");

payButton.addEventListener("click", async function (e) {
  e.preventDefault(); // Perbaikan tanda kurung
  const formData = new FormData(form); // Huruf besar pada FormData
  const data = new URLSearchParams(formData);

  // Mengambil nilai input
  const user = document.querySelector('input[name="user"]').value;
  const alamat = document.querySelector('input[name="alamat"]').value;
  const kontak = document.querySelector('input[name="kontak"]').value;

  try {
    const response = await fetch("function/payment.php", {
      method: "POST",
      body: data,
    });
    // const snapToken = await response.text(); // Mengasumsikan server hanya mengembalikan token sebagai teks sederhana
    const jsonResponse = await response.json(); // Ganti yang awalnya hanya menerima snap, menjadi juga menerima orderID
    const snapToken = jsonResponse.snapToken;
    const orderId = jsonResponse.orderId;

    // memasukkan data untuk dikirim ke whatsapp
    const dataUser = {
      user: user,
      alamat: alamat,
      kontak: kontak,
      orderId: orderId,
    };

    // Gunakan token untuk membuka Snap popup
    window.snap.pay(snapToken, {
      onSuccess: function (result) {
        // Data yang akan dikirim ke server untuk update transaksi
        var postData = {
          items: JSON.stringify(data), // Contoh data
        };

        // Melakukan update transaksi terlebih dahulu
        $.ajax({
          url: "function/update_transaction.php", // URL ke file PHP untuk update transaksi
          type: "POST",
          data: postData,
          dataType: "json",
          success: function (response) {
            if (response.success) {
              // Setelah berhasil update transaksi, kirim pesan WhatsApp
              fetch("function/sendWhatsApp.php", {
                method: "POST",
                headers: {
                  "Content-Type": "application/json",
                },
                body: JSON.stringify(dataUser), // Data yang diperlukan untuk pesan WhatsApp
              })
                .then((response) => response.json())
                .then((data) => {
                  console.log("Pesan WhatsApp terkirim:", data);
                  // Tampilkan pemberitahuan pembayaran berhasil
                  Swal.fire({
                    title: "Pembayaran Berhasil!",
                    text: "Pesanan Anda akan diproses segera!",
                    icon: "success",
                  }).then((result) => {
                    if (result.value) {
                      location.reload(); // Refresh halaman
                    }
                  });
                })
                .catch((error) => {
                  console.error("Error mengirim pesan WhatsApp:", error.message);
                  Swal.fire("Error", "Tidak dapat mengirim pesan WhatsApp.", "error");
                });
            } else {
              Swal.fire("Error", "Ada masalah saat memproses pembayaran.", "error");
            }
          },
          error: function () {
            Swal.fire("Error", "Tidak dapat terhubung ke server.", "error");
          },
        });
      },
      onPending: function (result) {
        Swal.fire({
          icon: "info",
          title: "Pembayaran Pending",
          text: "Silahkan lakukan pembayaran. Jika terjadi kesalahan mulai ulang website",
        });
      },
      onError: function (result) {
        Swal.fire({
          icon: "error",
          title: "Pembayaran Gagal",
          text: "pembayaran Expired, silahkan lakukan pemesanan dan pembayaran kembali",
        });
      },
      onClose: function () {
        Swal.fire({
          icon: "warning",
          title: "Kamu keluar sebelum melanjutkan pemabayaran",
          text: "Silahkan lakukan pemesanan dan pembayaran kembali. Jika terjadi kesalahan mulai ulang website",
        });
      },
    });
  } catch (error) {
    console.log(error.message);
  }
});
