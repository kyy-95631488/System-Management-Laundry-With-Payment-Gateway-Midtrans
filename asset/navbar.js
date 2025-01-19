// Pastikan script dijalankan setelah DOM sepenuhnya dimuat
document.addEventListener("DOMContentLoaded", function () {
  // Mendapatkan semua elemen nav item
  const navItems = document.querySelectorAll("#nav .nav-item");

  navItems.forEach((navItem) => {
    navItem.addEventListener("click", function () {
      // Menghapus class aktif dari semua nav item
      navItems.forEach((item) => {
        item.className = "nav-item block py-2 px-3 text-lg font-bold text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-sky-700 md:p-0";
      });

      // Menambahkan class aktif ke nav item yang diklik
      this.className = "nav-item block py-2 px-3 text-lg font-bold text-white bg-sky-700 rounded md:bg-transparent md:text-sky-700 md:p-0";
    });
  });
});
