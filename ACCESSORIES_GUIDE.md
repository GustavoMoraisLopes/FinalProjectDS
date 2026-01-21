# Sistema de Acessórios - Requisições em Cascata 🚀

## Descrição

O LabStock agora suporta:
1. **Requisições em cascata** com acessórios pré-configurados
2. **Tipos de equipamento** para melhor categorização e organização
3. **Associação automática** de tipos de equipamento a equipamentos específicos

Quando um utilizador requisita um equipamento (ex: câmara), o sistema carrega automaticamente os acessórios recomendados (baterias, cartões de memória, carregadores, etc).

## Como Funciona

### 1. **Requisição Simplificada para o Utilizador**
```
1. Utilizador clica em "Nova Requisição"
2. Seleciona um equipamento (ex: Câmara)
3. Sistema carrega AUTOMATICAMENTE os acessórios
4. Acessórios vêm por DEFAULT marcados ✓
5. Utilizador pode alterar quantidades ou desmarcar
6. Submete a requisição COM TUDO (equipamento + acessórios)
```

### 2. **Configuração de Tipos de Equipamento**

Os tipos de equipamento disponíveis incluem:
- **Câmara de Foto** - Câmaras fotográficas profissionais
- **Câmara de Vídeo** - Câmaras para gravação de vídeo
- **Lente** - Lentes e objetivas para câmaras
- **Cartão de Memória** - Cartões SD, CompactFlash, XQD, etc.
- **Bateria** - Baterias recarregáveis
- **Carregador** - Carregadores para baterias
- **Tripé** - Tripés e suportes
- **Iluminação** - Equipamentos de iluminação profissional
- **Microfone** - Microfones para áudio
- **Cabo/Acessório** - Cabos e adaptadores
- **Computador** - Desktop e portáteis
- **Monitor** - Monitores e ecrãs
- **Rato/Teclado** - Periféricos de entrada
- **Disco Externo** - Armazenamento externo
- **Projetor** - Projetores
- **Servidor** - Servidores
- **Switch/Router** - Equipamentos de rede
- **Webcam** - Câmaras web
- **Headset** - Auscultadores com microfone
- **Smartphone** - Telemóveis
- **Tablet** - Tablets
- **Outro** - Outro tipo de equipamento

Ao criar ou editar um equipamento, pode atribuir um tipo para melhor organização:
```
Novo Equipamento
├─ Nome: Canon EOS 5D Mark IV
├─ Categoria: Câmaras
├─ Tipo: Câmara de Foto  ← NOVO CAMPO
├─ S/N: ABC123456
└─ ...
```

### 3. **Configuração Admin**
O admin pode configurar quais são os acessórios padrão para cada equipamento:
- Aceder a `/admin/accessories`
- Selecionar um equipamento
- Adicionar acessórios com quantidades recomendadas
- Exemplo: Câmara → Bateria (qty: 1), Cartão (qty: 2), Carregador (qty: 1)

### 4. **Visualização do Inventário**
O inventário agora tem:
- **Acordeão de categorias** para melhor organização
- **Coluna de Tipo** para visualizar tipo de equipamento
- **Busca global** (nome + S/N)
- **Filtro de estado** (Disponível/Emprestado/Manutenção/Indisponível)
```
Equipamentos
├─ Câmaras (5)
│  └─ Canon EOS 5D [Câmara de Foto] ...
│  └─ Sony A7III [Câmara de Foto] ...
├─ Computadores (3)
│  └─ Dell XPS [Computador] ...
```

## Estrutura de Base de Dados

### Tabela `equipment_types`
```
- id
- name (ex: "Câmara de Foto", "Lente")
- description (descrição detalhada)
- icon (Bootstrap icon class)
- timestamps
```

### Tabela `equipments` (atualizada)
```
- id
- name
- category_id (FK)
- equipment_type_id (FK) ← NOVO CAMPO
- serial_number
- location
- status
- ...
```

### Tabela `equipment_accessories`
```
- id
- equipment_id (FK)
- accessory_id (FK)
- default_quantity (padrão sugerido)
- notes (observações)
```

### Tabela `reservation_items`
```
- id
- reservation_id (FK)
- equipment_id (FK)
- quantity
- item_type ('main' ou 'accessory')
```

## Endpoints da API

### Carregar Acessórios (Cascata)
```
GET /api/accessories/{equipmentId}

Response:
{
  "success": true,
  "accessories": [
    {
      "id": 5,
      "name": "Bateria Canon NB-13L",
      "category": "Acessórios",
      "default_quantity": 1,
      "status": "available"
    }
  ]
}
```

### Verificar Disponibilidade
```
GET /api/check-availability/{equipmentId}

Response:
{
  "available": true,
  "status": "available",
  "current_location": "Armário C"
}
```

## Rotas Novas

### Para Utilizadores (Requisições)
- `GET /reservations/create` - Formulário com cascata
- `POST /reservations` - Submeter requisição com acessórios
- `GET /api/accessories/{id}` - API para carregar acessórios

### Para Admin (Gestão)
- `GET /admin/accessories` - Painel de configuração
- `POST /admin/equipment/{equipment}/attach-accessory` - Adicionar acessório
- `DELETE /admin/equipment/{equipment}/accessory/{accessory}` - Remover acessório
- `PUT /admin/equipment/{equipment}/accessory/{accessory}` - Atualizar quantidade

## Exemplo de Uso

### Criando uma Requisição com Acessórios
```php
// Frontend (JavaScript)
const equipmentSelect = document.getElementById('equipment_id');

equipmentSelect.addEventListener('change', async () => {
    const equipmentId = equipmentSelect.value;
    const response = await fetch(`/api/accessories/${equipmentId}`);
    const data = await response.json();
    
    // Renderizar acessórios no formulário
    data.accessories.forEach(accessory => {
        // Criar checkbox já marcado
        // Permitir alterar quantidade
    });
});

// Backend (Laravel)
// No controller, o store() já cria ReservationItems para cada acessório
$reservation = Reservation::create($validated);

// Adicionar item principal
ReservationItem::create([
    'reservation_id' => $reservation->id,
    'equipment_id' => $validated['equipment_id'],
    'quantity' => 1,
    'item_type' => 'main',
]);

// Adicionar acessórios
foreach ($validated['accessories'] as $accessory) {
    ReservationItem::create([
        'reservation_id' => $reservation->id,
        'equipment_id' => $accessory['equipment_id'],
        'quantity' => $accessory['quantity'],
        'item_type' => 'accessory',
    ]);
}
```

## Populate de Teste

Para testar com dados de exemplo:
```bash
php artisan db:seed --class=AccessoriesSeeder
```

Isto criará acessórios de teste para câmaras e laptops.

## Fluxo Completo de Requisição

```
┌─────────────────────────────────┐
│    Nova Requisição              │
│  1. Selecionar Equipamento      │
│  2. Carregar Acessórios (AJAX)  │
│  3. Alterar Quantidades (opt)   │
│  4. Submeter com Acessórios     │
└──────────┬──────────────────────┘
           │
           v
┌─────────────────────────────────┐
│    Reservation criada           │
│  - equipment_id (principal)     │
│  - reservation_items (todos)    │
│    ├─ main (câmara)            │
│    ├─ accessory (bateria)      │
│    ├─ accessory (cartão x2)    │
│    └─ accessory (carregador)   │
└──────────┬──────────────────────┘
           │
           v
┌─────────────────────────────────┐
│    Admin aprova/rejeita         │
│    (toda a requisição de uma   │
│     vez com acessórios)         │
└─────────────────────────────────┘
```

## Notas Importantes

✅ **Acessórios vêm marcados por defeito** - O utilizador vê logo quais são recomendados
✅ **Quantidades ajustáveis** - Pode alterar no formulário
✅ **Uma só requisição** - Tudo num só passo
✅ **Organização por categoria** - Melhor visualização do inventário
✅ **Escalável** - Fácil adicionar mais acessórios no futuro

## Troubleshooting

Se os acessórios não aparecem:
1. Confirmar que estão ligados na base de dados (`equipment_accessories`)
2. Testar a API: `GET /api/accessories/1` (substituir 1 pelo ID)
3. Verificar a consola do navegador (F12) para erros JavaScript

## Versão
- **Criado**: 20 de Janeiro de 2026
- **Status**: ✅ Implementado e funcional
