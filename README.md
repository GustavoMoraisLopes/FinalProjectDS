# LabStock - Sistema de Gestão de Inventário

<div align="center">
  <img src="https://img.shields.io/badge/Laravel-11-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel">
  <img src="https://img.shields.io/badge/PHP-8.4-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP">
  <img src="https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL">
  <img src="https://img.shields.io/badge/Bootstrap-5.3-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white" alt="Bootstrap">
</div>

---

## 📋 Sobre o Projeto

**LabStock** é um sistema web moderno e intuitivo para gestão de inventário informático desenvolvido como projeto final do curso de **Desenvolvimento de Software** no **ISTEC Porto**. O sistema permite a gestão eficiente de equipamentos, requisições, utilizadores e auditoria completa de ações.

### 🎓 Contexto Académico
- **Instituição**: ISTEC Porto / IPTA Porto
- **Curso**: CTeSP Desenvolvimento de Software
- **Ano Letivo**: 2025/2026
- **Tecnologias**: Laravel 11, MySQL, Bootstrap 5

---

## ✨ Funcionalidades Principais

### 🔐 Autenticação e Autorização
- Sistema completo de login/registro com validação de emails institucionais
- Dois níveis de acesso: **Admin** e **User**
- Diferenciação entre **Alunos** e **Professores** com aprovação admin
- Notificações por email em ações críticas (login, alterações de perfil, password)

### 📊 Dashboard
- Visão geral com estatísticas em tempo real
- Contador de equipamentos disponíveis/requisitados
- Requisições recentes e equipamentos mais utilizados
- Interface responsiva e moderna

### 🖥️ Gestão de Inventário
- CRUD completo de equipamentos (criar, editar, eliminar)
- Filtros avançados por tipo, disponibilidade, localização
- Upload de imagens de equipamentos
- Estados automáticos (disponível, requisitado, em manutenção)
- Histórico completo de requisições por equipamento

### 📝 Sistema de Requisições
- **Para Alunos**: Auto-preenchimento de dados académicos do perfil
  - Instituição, Tipo de Curso, Curso, Turma/Ano
  - Comboboxes em cascata com dados reais do ISTEC e IPTA
- **Para Professores**: Seleção manual de contexto académico
- Aprovação/rejeição por administradores
- Check-out e check-in de equipamentos
- Lembretes automáticos de devolução
- Notificações por email em todas as etapas

### 👥 Gestão de Utilizadores (Admin)
- Lista completa com badges de tipo (Admin/User, Professor/Aluno)
- **Sistema de Aprovação de Professores**:
  - Alunos solicitam acesso como professor no perfil
  - Admins aprovam ou rejeitam pedidos
  - Badge "Pedido Pendente" visível na lista
  - Audit log completo de aprovações/rejeições
- Tabela responsiva com sticky header

### 📜 Audit Logs
- Rastreamento completo de todas as ações no sistema
- Filtros por ação e utilizador
- Descrições em português (legível para júri)
- Informações: Data/Hora, Utilizador, Ação, Modelo, Descrição

### 🔍 Scanner
- Busca rápida de equipamentos por código de série ou tag
- Interface intuitiva com resultados instantâneos

### 🔔 Sistema de Notificações
- Emails automáticos para:
  - Login em nova localização
  - Alteração de perfil ou password
  - Requisição criada/aprovada/rejeitada
  - Lembretes de devolução de equipamento

### 👤 Perfil de Utilizador
- Upload de avatar personalizado
- Edição de dados pessoais (nome, email, telefone, departamento)
- **Dados Académicos** (para alunos):
  - Comboboxes em cascata com instituições e cursos reais
  - Auto-preenchimento em requisições
- Botão de solicitação de acesso como professor
- Alteração segura de password

---

## 🚀 Instalação e Configuração

### Pré-requisitos
- PHP 8.4+
- Composer
- MySQL 8.0+
- Node.js & npm (opcional para desenvolvimento frontend)

### Passos de Instalação

1. **Clone o repositório**
```bash
git clone https://github.com/GustavoMoraisLopes/FinalProjectDS.git
cd labstock
```

2. **Instale dependências**
```bash
composer install
npm install && npm run build
```

3. **Configure o ambiente**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Configure o banco de dados** no ficheiro `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=labstock
DB_USERNAME=root
DB_PASSWORD=
```

5. **Execute as migrations**
```bash
php artisan migrate
```

6. **Inicie o servidor**
```bash
php artisan serve
```

Aceda a **http://127.0.0.1:8000**

7. **Crie o primeiro utilizador** através do formulário de registro e configure-o como administrador diretamente na base de dados, alterando o campo `role` para `'admin'`.

---

## 🛠️ Tecnologias Utilizadas

### Backend
- **Laravel 11** - Framework PHP moderno
- **MySQL 8** - Base de dados relacional
- **PHP 8.4** - Linguagem de programação

### Frontend
- **Bootstrap 5.3** - Framework CSS responsivo
- **Blade Templates** - Motor de templates do Laravel
- **Bootstrap Icons** - Biblioteca de ícones
- **JavaScript Vanilla** - Lógica de cascata e interatividade

### Funcionalidades Avançadas
- **Eloquent ORM** - Gestão de modelos e relações
- **Migrations & Seeders** - Versionamento de base de dados
- **Middleware** - Controlo de acesso e autenticação
- **Notifications** - Sistema de emails automáticos
- **Policies** - Autorização granular
- **Audit Logging** - Rastreamento completo de ações

---

## 📁 Estrutura do Projeto

```
labstock/
├── app/
│   ├── Http/Controllers/     # Controladores (Admin, Profile, Reservation, etc.)
│   ├── Models/               # Modelos Eloquent (User, Equipment, Reservation, etc.)
│   ├── Notifications/        # Notificações por email
│   ├── Policies/             # Políticas de autorização
│   └── Services/             # Serviços (AuditLogger)
├── database/
│   ├── migrations/           # Migrações de base de dados
│   └── seeders/              # Dados iniciais
├── resources/
│   ├── views/                # Templates Blade
│   │   ├── admin/            # Vistas de administração
│   │   ├── auth/             # Login/Registro
│   │   ├── equipments/       # Gestão de equipamentos
│   │   ├── reservations/     # Requisições
│   │   └── profiles/         # Perfil de utilizador
│   └── css/                  # Estilos personalizados
├── routes/
│   └── web.php               # Rotas da aplicação
└── public/                   # Ficheiros públicos
```

---

## 🔑 Funcionalidades por Tipo de Utilizador

### 👨‍💼 Administrador
- ✅ Gestão completa de equipamentos
- ✅ Aprovar/rejeitar requisições
- ✅ Gestão de utilizadores
- ✅ Aprovação de pedidos de acesso como professor
- ✅ Acesso aos audit logs
- ✅ Check-out e check-in de equipamentos

### 👨‍🎓 Aluno
- ✅ Ver inventário disponível
- ✅ Criar requisições com dados académicos auto-preenchidos
- ✅ Acompanhar estado das suas requisições
- ✅ Solicitar acesso como professor
- ✅ Editar perfil e dados académicos
- ✅ Receber notificações por email

### 👨‍🏫 Professor
- ✅ Ver inventário disponível
- ✅ Criar requisições com seleção manual de contexto académico
- ✅ Acompanhar estado das suas requisições
- ✅ Editar perfil
- ✅ Receber notificações por email

---

## 📸 Screenshots

(<img width="1919" height="1160" alt="screenshot_01_login" src="https://github.com/user-attachments/assets/8ae0d52a-89c0-47c3-88b1-8ba85a742cad" />
<img width="1919" height="1160" alt="screenshot_02_register" src="https://github.com/user-attachments/assets/84288e63-0199-4941-8424-9d67fc911c88" />
<img width="1919" height="1159" alt="screenshot_03_dashboard" src="https://github.com/user-attachments/assets/8256e226-02e1-4946-bc2d-4d42d55881ab" />
<img width="1919" height="1164" alt="screenshot_07_reservation_create" src="https://github.com/user-attachments/assets/9b838300-9ead-4cd3-98d5-fdb02df89a20" />
<img width="1919" height="1157" alt="screenshot_15_notifications" src="https://github.com/user-attachments/assets/c6e2dc56-525d-4bbd-b49a-cdcf1ba423c8" />
<img width="429" height="931" alt="screenshot_16_mobile_dashboard" src="https://github.com/user-attachments/assets/7dac68b4-f96b-4009-8214-f200cb0f91ef" />
<img width="431" height="934" alt="screenshot_17_mobile_sidebar" src="https://github.com/user-attachments/assets/031ea1de-1fde-452c-a46d-0b8fb050ccad" />







---

## 🤝 Contribuições

Este projeto foi desenvolvido como trabalho final de curso e não aceita contribuições externas no momento.

---

## 📄 Licença

Este projeto é open-source e está disponível sob a licença **MIT**.

---

## 👨‍💻 Autor

**Gustavo Morais Pereira Lopes**
- Ano: 2025/2026
---

<div align="center">
  <p>Desenvolvido em Laravel</p>
</div>

