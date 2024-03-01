document.getElementById('contactForm').addEventListener('submit', function (event) {
    event.preventDefault();
    // フォームデータの取得
    var formData = new FormData(this); // thisを使用して簡単にFormDataオブジェクトを作成

    // 確認ダイアログ
    var confirmMessage = `Are you sure you want to send the following information?\n\n`;
    formData.forEach(function (value, key) {
        confirmMessage += `${key}: ${value}\n`;
    });

    if (!confirm(confirmMessage)) {
        console.log('Submission has been canceled.');
        return; // キャンセルされた場合はここで処理を中断
    }

    fetch('sendMessage.php', {
        method: 'POST',
        body: formData
    })
        .then(response => {
            if (!response.ok) {
                throw new Error('Invalid network response.');
            }
            return response.text();
        })
        .then(data => alert('Message has been successfully sent.'))
        .catch(error => alert('An error has occurred: ' + error));
});
