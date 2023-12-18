function updateTask(stageId, taskId, userId) {
    return new Promise((resolve, reject) => {
        const requestUrl = '/telegram/update-task';
        const xhr = new XMLHttpRequest();
        let data = new FormData();
        data.append('stage_id', stageId);
        data.append('user_id', userId);
        data.append('task_id', taskId);
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


let container = document.getElementById('header-menu');

document.getElementById('menu-btn').addEventListener('click', function (event) {
    event.preventDefault();

    if (!container.classList.contains('active')) {
        container.classList.add('active');
        container.style.height = 'auto';

        let height = container.clientHeight + "px";

        container.style.height = '0px';

        setTimeout(function () {
            container.style.height = height;
        }, 0);
    } else {
        container.style.height = '0px';

        container.addEventListener('transitionend', function () {
            container.classList.remove('active');
        }, {
            once: true
        });
    }
});

document.getElementById('change-stage').addEventListener('click', function () {
    let stageId = this.getAttribute('data-stage');
    let taskId = this.getAttribute('data-task');
    let userId = this.getAttribute('data-user');

    if(stageId != 0) {
        let result = updateTask(stageId, taskId, userId)
            .then(data => {
                let dataInfo = JSON.parse(data)
                if(dataInfo.msg == 'success') {
                    window.location.reload()
                }
            })
    }
})