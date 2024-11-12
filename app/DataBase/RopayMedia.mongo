--Base de datos llamada RopayMedia
use RopayMedia
db.createCollection("roles")
db.createCollection("categorias")
db.createCollection("usuarios")
db.createCollection("productos")
db.createCollection("pedidos")
db.createCollection("facturas")

--Roles
db.roles.insertMany([
  {
    "id_rol": 1,
    "nombre_rol": "Administrador"
  },
  {
    "id_rol": 2,
    "nombre_rol": "Usuario"
  }
]);

--Categorias
db.categorias.insertMany([
  {
    "id_categoria": 1,
    "nombre_categoria": "Hombre"
  },
  {
    "id_categoria": 2,
    "nombre_categoria": "Mujer"
  },
  {
    "id_categoria": 3,
    "nombre_categoria": "Niños"
  }
]);

--Usuarios
db.usuarios.insertMany([
  {
    "id_usuario": 1,
    "nombre": "Bryan",
    "apellido": "Davila Mendez",
    "correo": "bdavila@admin.com",
    "telefono": "61862241",
    "id_rol": 1
  },
  {
    "id_usuario": 2,
    "nombre": "Fabian",
    "apellido": "Mata Tencio",
    "correo": "fmata@admin.com",
    "telefono": "85739393",
    "id_rol": 1
  },
  {
    "id_usuario": 3,
    "nombre": "Josue",
    "apellido": "Zamora Conejo",
    "correo": "jzamora@admin.com",
    "telefono": "60760022",
    "id_rol": 1
  },
  {
    "id_usuario": 4,
    "nombre": "Maria",
    "apellido": "Canales Carvajal",
    "correo": "mcanales@admin.com",
    "telefono": "64229826",
    "id_rol": 1
  },
  {
    "id_usuario": 5,
    "nombre": "Andres",
    "apellido": "Mendez Davila",
    "correo": "amendez@gmail.com",
    "telefono": "61234567",
    "id_rol": 2
  }
]);

--Productos
db.productos.insertMany([
  {
    "id_producto": 1,
    "nombre_producto": "Camisa polo",
    "descripcion": "Camisa tipo polo para hombre",
    "precio": 10500,
    "stock": 100,
    "id_categoria": 1,
    "ruta_imagen": "https://caterpillarcr.com/cdn/shop/files/30076343.jpg?v=1705699698"
  },
  {
    "id_producto": 2,
    "nombre_producto": "Blusa",
    "descripcion": "Blusa con cuello en V",
    "precio": 7000,
    "stock": 50,
    "id_categoria": 2,
    "ruta_imagen": "https://www.bellayvale.pe/wp-content/uploads/2020/11/blusa-blanca-cuello-v-encaje-3.jpg"
  },
  {
    "id_producto": 3,
    "nombre_producto": "Pantalon jeans",
    "descripcion": "Pantalon jeans azul para niño",
    "precio": 5000,
    "stock": 50,
    "id_categoria": 3,
    "ruta_imagen": "https://siman.vtexassets.com/arquivos/ids/3095315-1600-auto?v=637927808270730000&width=1600&height=auto&aspect=true"
  }
]);

--Pedidos
db.pedidos.insertMany([
  {
    "id_pedido": 1,
    "fecha": new Date("2024-11-09T12:30:00Z"),
    "id_usuario": 4,
    "monto": 10500,
    "productos": [
      {
        "id_producto": 1,
        "cantidad": 1,
        "total": 10500
      }
    ],
    "total": 10500
  }
]);

--Facturas
db.facturas.insertMany([
  {
    "id_factura": 1,
    "id_usuario": 4,
    "fecha_emision": new Date("2024-11-09T12:30:00Z"),
    "total": 10500,
    "id_pedido": 1
  }
]);