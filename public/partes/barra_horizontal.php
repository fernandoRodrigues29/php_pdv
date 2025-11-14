        <nav>
            <h1>Gerenciamento de Produtos</h1>
            <div class="nav-menu">
                <button class="nav-toggle-btn" @click="toggleSidebarCollapse">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="user" @click="toggleDropdown">
                    Usuário <i class="fas fa-caret-down"></i>
                </div>
                <div class="dropdown" :class="{ 'show': showDropdown }">
                    <a href="#" @click="logout"><i class="fas fa-sign-out-alt"></i> Sair</a>
                </div>
            </div>
        </nav>

        <button class="toggle-btn" @click="toggleSidebar">
            <i class="fas fa-bars"></i>
        </button>