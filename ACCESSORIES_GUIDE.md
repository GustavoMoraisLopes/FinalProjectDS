# Sistema de Acessórios - Requisições em Cascata 🚀

## Descrição

O LabStock agora suporta **requisições em cascata** com acessórios pré-configurados. Quando um utilizador requisita um equipamento (ex: câmara), o sistema carrega automaticamente os acessórios recomendados (baterias, cartões de memória, carregadores, etc).

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

### 2. **Configuração Admin**
O admin pode configurar quais são os acessórios padrão para cada equipamento:
- Aceder a `/admin/accessories`
- Selecionar um equipamento
- Adicionar acessórios com quantidades recomendadas
- Exemplo: Câmara → Bateria (qty: 1), Cartão (qty: 2), Carregador (qty: 1)

### 3. **Visualização do Inventário**
O inventário agora tem **abas por categoria** para melhor organização:
```
[Todas] [Câmaras] [Computadores] [Periféricos] [Acessórios] ...
```

## Estrutura de Base de Dados

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
