username='homestead'
password='secret'
db='homestead'
if [ $# -eq 1 ]
then
  db=$1
fi
mysql -u $username -p$password -e "DROP DATABASE $db; CREATE DATABASE $db;"
php artisan migrate --seed
