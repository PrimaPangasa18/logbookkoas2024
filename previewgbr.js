/* begin Page */
/* Created by Aris Triwiyatno */

function changeProfile() {
  $("#image").click();
}
$("#image").change(function () {
  var imgPath = this.value;
  var ext = imgPath.substring(imgPath.lastIndexOf(".") + 1).toLowerCase();
  if (ext == "gif" || ext == "png" || ext == "jpg" || ext == "jpeg") {
    if (this.files[0].size > 100000)
      alert("Maaf. File Terlalu Besar ! Maksimal Upload 100 KB");
    else readURL(this);
  } else alert("Tipe file tidak sesuai. Foto harus bertipe .png, .gif atau .jpg.");
});
function readURL(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();
    reader.readAsDataURL(input.files[0]);
    reader.onload = function (e) {
      $("#preview").attr("src", e.target.result);
      //              $("#remove").val(0);
    };
  }
}
function removeImage() {
  $("#preview").attr("src", "foto/profil_blank.png");
  //      $("#remove").val(1);
}
