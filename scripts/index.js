// Função para exibir dados na tabela
function showData() {
    const url = 'http://127.0.0.1:80/crud/read.php';
    fetch(url, {
        method: "GET"
    })
    .then(response => response.json())  // Recebe a resposta como JSON
    .then(data => {
        if (data.message) {
            alert(data.message);  // Exibe mensagem se houver erro
        } else {
            let html = '';
            data.forEach(user => {
                html += `
                    <tr>
                        <td>${user.name}</td>
                        <td>${user.cpf}</td>
                        <td>${user.address}</td>
                        <td>${user.phone}</td>
                        <td>${user.email}</td>
                        <td>
                            <button class="btn btn-danger" onclick="remove(${user.id})">Excluir</button>
                            <button class="btn btn-warning" onclick="editUser(${user.id})">Editar</button>
                        </td>
                    </tr>
                `;
            });
            document.getElementById('results').innerHTML = html;
        }
    })
    .catch(err => console.error('Erro ao buscar os dados:', err));
}



// Função para criar usuário
function createUser() {
    const name = document.getElementById('name').value;
    const cpf = document.getElementById('cpf').value;
    const address = document.getElementById('address').value;
    const telephone = document.getElementById('phone').value;
    const email = document.getElementById('email').value;

    const form = new FormData();
    form.append('name', name);
    form.append('cpf', cpf);
    form.append('address', address);
    form.append('phone', telephone);
    form.append('email', email);

    const url = 'http://127.0.0.1:80/crud/cadastro.php';

    fetch(url, {
        method: 'POST',
        body: form
    })
    .then(response => response.json())  // Parseia como JSON
    .then(result => {
        Swal.fire(result.message);  // Exibe a mensagem retornada
        if (result.status === 'success') {
            showData();  // Atualiza a tabela após criar o usuário
        }
    })
    .catch(err => console.log(err));
}

function remove(id) {
    console.log('ID a ser removido:', id);  // Verifica se o ID está correto
    if (confirm('Tem certeza que deseja excluir este cliente?')) {
        const form = new FormData();
        form.append('id', id);  // Adiciona o ID ao formulário de dados

        const url = 'http://127.0.0.1:80/crud/remove.php';

        fetch(url, {
            method: "POST",
            body: form
        })
        .then(response => response.json())
        .then(data => {
            if (data.message) {
                alert(data.message);
            }
            showData();  // Atualiza a tabela após remover o usuário
        })
        .catch(err => {
            console.error('Erro ao remover:', err);
            alert('Erro ao remover cliente');
        });
    }
}


function editUser(id) {
    const url = `http://127.0.0.1:80/crud/read_single.php?id=${id}`;  // URL para obter os dados de um único cliente

    fetch(url)
        .then(response => response.json())
        .then(data => {
            // Preencher o formulário com os dados do cliente
            document.getElementById('editId').value = data.id;
            document.getElementById('editName').value = data.name;
            document.getElementById('editCpf').value = data.cpf;
            document.getElementById('editAddress').value = data.address;
            document.getElementById('editPhone').value = data.phone;
            document.getElementById('editEmail').value = data.email;
        })
        .catch(error => {
            console.log('Erro ao buscar os dados do cliente:', error);
            alert('Erro ao carregar os dados do cliente.');
        });
}

function updateUser() {
    const id = document.getElementById('editId').value;
    const name = document.getElementById('editName').value;
    const cpf = document.getElementById('editCpf').value;
    const address = document.getElementById('editAddress').value;
    const phone = document.getElementById('editPhone').value;
    const email = document.getElementById('editEmail').value;

    const form = new FormData();
    form.append('id', id);
    form.append('name', name);
    form.append('cpf', cpf);
    form.append('address', address);
    form.append('phone', phone);
    form.append('email', email);

    const url = 'http://127.0.0.1:80/crud/scripts/update.php';  // URL do arquivo PHP de atualização

    fetch(url, {
        method: 'POST',
        body: form
    })
    .then(response => response.json())
    .then(result => {
        alert(result.message);  // Exibe a mensagem de sucesso ou erro
        if (result.message === 'Usuário atualizado com sucesso') {
            showData();  // Atualiza a tabela após editar o usuário
        }
    })
    .catch(err => console.log(err));
}


showData();  // Exibe os dados ao carregar a página
