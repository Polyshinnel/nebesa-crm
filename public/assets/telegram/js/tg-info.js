function getUser(telegramId) {
    return new Promise((resolve, reject) => {
        const requestUrl = '/telegram/check-user';
        const xhr = new XMLHttpRequest();
        let data = new FormData();
        data.append('telegram_id', telegramId);
        xhr.open('POST', requestUrl)
        xhr.onload = () => {
            if(xhr.status >= 400) {
                reject(xhr.response)
            } else {
                resolve(xhr.response)
            }
        }

        xhr.onerror = () => {
            reject(xhr.response)
        }
        xhr.send(data);
    })
}

let tg = window.Telegram.WebApp;
let tgUserId = String(tg.initDataUnsafe.user.id)
document.getElementById('telegram_id').value = tgUserId

let userInfo = getUser(tgUserId)
    .then(data => {
        let dataInfo = JSON.parse(data)
        if(dataInfo.user_id != undefined) {
            let url = '/telegram/new-tasks?user_id='+dataInfo.user_id
            window.location.replace(url)
        }
    })