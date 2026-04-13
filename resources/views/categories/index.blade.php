<!DOCTYPE html>
<html>
<head>
    <title>Categorias</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        body { font-family: Arial; padding: 20px; }
        input, button { padding: 8px; margin: 5px; }
        table { width: 100%; margin-top: 20px; border-collapse: collapse; }
        th, td { border: 1px solid #ccc; padding: 10px; }
    </style>
</head>
<body>

<h2>Categorias</h2>

<!-- Criar -->
<input type="text" id="name" placeholder="Nome da categoria">
<button onclick="createCategory()">Criar</button>

<!-- Tabela -->
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody id="categoryTable"></tbody>
</table>

<!-- Modal simples -->
<div id="modal" style="display:none; background:#00000090; position:fixed; top:0; left:0; width:100%; height:100%;">
    <div style="background:#fff; padding:20px; margin:100px auto; width:300px;">
        <h3>Editar</h3>
        <input type="text" id="editName">
        <button onclick="updateCategory()">Salvar</button>
        <button onclick="closeModal()">Cancelar</button>
    </div>
</div>

<script>
let currentId = null;

const api = '/api/categories';

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

// 🔹 Listar
function loadCategories() {
    $.get(api, function(data) {
        let rows = '';

        data.forEach(cat => {
            rows += `
                <tr>
                    <td>${cat.id}</td>
                    <td>${cat.name}</td>
                    <td>
                        <button onclick="openModal(${cat.id}, '${cat.name}')">Editar</button>
                        <button onclick="deleteCategory(${cat.id})">Excluir</button>
                    </td>
                </tr>
            `;
        });

        $('#categoryTable').html(rows);
    });
}

// 🔹 Criar
function createCategory() {
    let name = $('#name').val();

    $.post(api, { name }, function() {
        $('#name').val('');
        loadCategories();
    });
}

// 🔹 Abrir modal
function openModal(id, name) {
    currentId = id;
    $('#editName').val(name);
    $('#modal').show();
}

// 🔹 Fechar modal
function closeModal() {
    $('#modal').hide();
}

// 🔹 Atualizar
function updateCategory() {
    let name = $('#editName').val();

    $.ajax({
        url: api + '/' + currentId,
        type: 'PUT',
        data: { name },
        success: function() {
            closeModal();
            loadCategories();
        }
    });
}

// 🔹 Deletar
function deleteCategory(id) {
    if (!confirm('Tem certeza?')) return;

    $.ajax({
        url: api + '/' + id,
        type: 'DELETE',
        success: function() {
            loadCategories();
        },
        error: function(err) {
            alert(err.responseJSON.error);
        }
    });
}

// Init
loadCategories();
</script>

</body>
</html>