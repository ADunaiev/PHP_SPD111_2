<h1>API та бекенд</h1>

<div class="card-panel light-blue">
    <button class="btn wave-effect waves-light" onclick="getClick()">CREATE</button>
    <button class="btn wave-effect waves-light" onclick="postClick()">POST</button>
    <div id="api-result"></div>
</div>
<h2>Робота з БД</h2>
<p>
    <h6>Підготовчі роботи.</h6>
    Встановлюємо СУБД (MySQL/MariaDB).<br/>
    Створюємо окрему базу даних для проєкту, користувача для неї.<br/> 
    <code>CREATE DATABASE php_spd_111</code><br/>
    <code>CREATE USER 'spd_111_user'@'localhost' IDENTIFIED BY 'spd_pass'</code><br/>
    Даємо користувачу права на дану БД
    <code>GRANT ALL PRIVILEGES ON php_spd_111.* TO 'spd_111_user'@'localhost'</code><br/>
    Оновлюємо таблицю доступа<br/>
    <code>FLUSH PRIVELEGES</code><br/>
    <code></code><br/>
</p>
<p>
    <h6>Підключення.</h6>
    Є дві группи технологій работи з БД: "індівідуальні" - набори команд 
    під кожну БД окремо (mysql_connect(), ib_connect(),...) 
    та "універсальна" - технологія PDO (аналог ADO на .NET)
    Далі розглядаємо PDO. 
</p>
<script>
function getClick() {
    fetch("/test")
    .then(r => r.text())
    .then(t => {
        document.getElementById("api-result").innerText = t;
    })
}
function postClick() {
    fetch("/test", {
        method: 'POST'
    })
    .then(r => r.text())
    .then(t => {
        document.getElementById("api-result").innerText = t;
    })
}
</script>