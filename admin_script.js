document.addEventListener('DOMContentLoaded', () => {
    const loginSection = document.getElementById('login-section');
    const adminPanel = document.getElementById('admin-panel');
    const loginForm = document.getElementById('login-form');
    const addGroupForm = document.getElementById('add-group-form');
    const logoutBtn = document.getElementById('logout-btn');
    const categoriaSelect = document.getElementById('categoria_id');

    // --- FUNÇÕES AUXILIARES ---
    const showAdminPanel = () => {
        loginSection.classList.add('hidden');
        adminPanel.classList.remove('hidden');
        loadCategories();
    };

    const showLogin = () => {
        loginSection.classList.remove('hidden');
        adminPanel.classList.add('hidden');
    };

    const showMessage = (elementId, message, isSuccess) => {
        const el = document.getElementById(elementId);
        el.textContent = message;
        el.className = `p-3 rounded-md text-sm ${isSuccess ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-700'}`;
        el.classList.remove('hidden');
        setTimeout(() => el.classList.add('hidden'), 5000);
    };

    // --- CARREGAR DADOS ---
    const loadCategories = async () => {
        try {
            // Este endpoint precisa ser criado (api/buscar_categorias.php)
            const response = await fetch('api/buscar_categorias.php');
            const result = await response.json();
            if (result.sucesso) {
                categoriaSelect.innerHTML = '<option value="">Selecione uma categoria</option>';
                result.dados.forEach(cat => {
                    categoriaSelect.innerHTML += `<option value="${cat.id}">${cat.nome}</option>`;
                });
            }
        } catch (error) {
            console.error("Erro ao carregar categorias:", error);
        }
    };

    // --- LÓGICA DE AUTENTICAÇÃO ---
    // Verificar se já existe uma sessão ativa
    const checkSession = async () => {
        try {
            // Este endpoint precisa ser criado (api/admin/verificar_sessao.php)
            const response = await fetch('api/admin/verificar_sessao.php');
            const result = await response.json();
            if (result.logado) {
                showAdminPanel();
            } else {
                showLogin();
            }
        } catch (error) {
            showLogin();
        }
    };

    // Evento de Login
    loginForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const usuario = document.getElementById('usuario').value;
        const senha = document.getElementById('senha').value;

        try {
            const response = await fetch('api/admin/login.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ usuario, senha })
            });
            const result = await response.json();
            if (result.sucesso) {
                showAdminPanel();
            } else {
                showMessage('login-error', result.mensagem, false);
            }
        } catch (error) {
            showMessage('login-error', 'Erro de conexão. Tente novamente.', false);
        }
    });

    // Evento de Logout
    logoutBtn.addEventListener('click', async () => {
        // Este endpoint precisa ser criado (api/admin/logout.php)
        await fetch('api/admin/logout.php');
        showLogin();
    });

    // --- LÓGICA DE ADICIONAR GRUPO ---
    addGroupForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(addGroupForm);
        const data = Object.fromEntries(formData.entries());

        try {
            const response = await fetch('api/admin/adicionar_grupo.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });
            const result = await response.json();
            showMessage('add-feedback', result.mensagem, result.sucesso);
            if(result.sucesso) {
                addGroupForm.reset();
            }
        } catch (error) {
            showMessage('add-feedback', 'Erro de conexão ao adicionar grupo.', false);
        }
    });

    // Iniciar verificação de sessão
    checkSession();
});
