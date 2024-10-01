import requests 
 
url = "https://logbook-fk.apps.undip.ac.id/koas/cetak_rekap_indstase.php" 
response = requests.get(url) 
 
with open("cetak_rekap_indstase.php", "wb") as f: 
    f.write(response.content) 