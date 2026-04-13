<!DOCTYPE html>
<html>

<head>
    <title>Chamados</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        body {
            font-family: Arial;
            padding: 20px;
        }

        input,
        select,
        button {
            padding: 8px;
            margin: 5px;
        }

        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 10px;
        }
    </style>
</head>

<body>

    <h2>Chamados</h2>

    <!-- 🔹 Criar -->
    <input type="text" id="title" placeholder="Título">
    <input type="text" id="description" placeholder="Descrição">

    <select id="category_id"></select>

    <button onclick="createTicket()">Criar</button>

    <hr>

    <!-- 🔹 Filtros -->
    <select id="filterStatus">
        <option value="">Todos status</option>
        <option value="aberto">Aberto</option>
        <option value="em_progresso">Em Progresso</option>
        <option value="resolvido">Resolvido</option>
    </select>

    <select id="filterCategory"></select>

    <button onclick="loadTickets()">Filtrar</button>

    <!-- 🔹 Tabela -->
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Título</th>
                <th>Status</th>
                <th>Categoria</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody id="ticketTable"></tbody>
    </table>

    <!-- 🔹 Modal -->
    <div id="modal" style="display:none; background:#00000090; position:fixed; top:0; left:0; width:100%; height:100%;">
        <div style="background:#fff; padding:20px; margin:100px auto; width:300px;">
            <h3>Editar</h3>

            <input type="text" id="editTitle">
            <input type="text" id="editDescription">

            <select id="editStatus">
                <option value="aberto">Aberto</option>
                <option value="em_progresso">Em Progresso</option>
                <option value="resolvido">Resolvido</option>
            </select>

            <select id="editCategory"></select>

            <button onclick="updateTicket()">Salvar</button>
            <button onclick="closeModal()">Cancelar</button>
        </div>
    </div>

    <script>
        let currentId = null;

        const api = '/api/tickets';
        const apiCategories = '/api/categories';

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // 🔹 Carregar categorias (selects)
        function loadCategories() {
            $.get(apiCategories, function(data) {
                let options = '<option value="">Categoria</option>';

                data.forEach(cat => {
                    options += `<option value="${cat.id}">${cat.name}</option>`;
                });

                $('#category_id').html(options);
                $('#filterCategory').html(options);
                $('#editCategory').html(options);
            });
        }

        // 🔹 Listar tickets com filtro
        function loadTickets() {
            let status = $('#filterStatus').val();
            let category = $('#filterCategory').val();

            $.get(api, {
                status: status,
                category_id: category
            }, function(data) {
                let rows = '';

                data.forEach(ticket => {
                    rows += `
                <tr>
                    <td>${ticket.id}</td>
                    <td>${ticket.title}</td>
                    <td>${formatStatus(ticket.status)}</td>
                    <td>${ticket.category?.name ?? ''}</td>
                    <td>
                        <button onclick='openModal(${JSON.stringify(ticket)})'>Editar</button>
                        <button onclick="deleteTicket(${ticket.id})">Excluir</button>
                    </td>
                </tr>
            `;
                });

                $('#ticketTable').html(rows);
            });
        }

        // 🔹 Criar
        function createTicket() {
            let data = {
                title: $('#title').val(),
                description: $('#description').val(),
                category_id: $('#category_id').val(),
                created_by: 'admin'
            };

            $.post(api, data, function() {
                loadTickets();
            });
        }

        // 🔹 Abrir modal
        function openModal(ticket) {
            currentId = ticket.id;

            $('#editTitle').val(ticket.title);
            $('#editDescription').val(ticket.description);
            $('#editStatus').val(ticket.status);
            $('#editCategory').val(ticket.category_id);

            $('#modal').show();
        }

        // 🔹 Fechar
        function closeModal() {
            $('#modal').hide();
        }

        // 🔹 Atualizar
        function updateTicket() {
            let data = {
                title: $('#editTitle').val(),
                description: $('#editDescription').val(),
                status: $('#editStatus').val(),
                category_id: $('#editCategory').val()
            };
            console.log(data);
            $.ajax({
                url: api + '/' + currentId,
                type: 'PUT',
                data: data,
                success: function() {
                    closeModal();
                    loadTickets();
                }
            });
        }

        // 🔹 Deletar
        function deleteTicket(id) {
            if (!confirm('Tem certeza?')) return;

            $.ajax({
                url: api + '/' + id,
                type: 'DELETE',
                success: function() {
                    loadTickets();
                }
            });
        }

        function formatStatus(status) {
            return status
                .replace('_', ' ')
                .replace(/\b\w/g, l => l.toUpperCase());
        }

        // Init
        loadCategories();
        loadTickets();
    </script>

</body>

</html>