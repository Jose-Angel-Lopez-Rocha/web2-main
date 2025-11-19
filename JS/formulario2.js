const { createApp } = Vue;

createApp({
  data() {
    return {
      marca: '',
      talla: '',
      color: '',
      stock: '',
      precio: '',
      categoria: '',
      terminos: false,

      valid: {
        marca: false,
        talla: false,
        color: false,
        stock: false,
        precio: false,
        categoria: false,
      },

      tocado: {
        marca: false,
        talla: false,
        color: false,
        stock: false,
        precio: false,
        categoria: false,
      },

      marcaExiste: null,


      error: false,
      exito: false,
      mensajeError: '',
      enviando: false
    };
  },

  methods: {

    async validarMarcaAjax() {
    if (!this.marca) {
        this.marcaExiste = null;
        return;
    }

    try {
        const formData = new FormData();
        formData.append("marca", this.marca);

        const respuesta = await fetch("verificar_marca.php", {
            method: "POST",
            body: formData
        });

        const data = await respuesta.json();
        this.marcaExiste = data.existe; // true o false

    } catch (err) {
        console.log("AJAX error:", err);
    }
},





    regex(campo) {
      const reglas = {
        marca: /^[a-zA-ZÀ-ÿ\s]{1,40}$/,
        color: /^[a-zA-ZÀ-ÿ\s]{1,40}$/,
        stock: /^[1-9]\d*$/,
        precio: /^\d+(\.\d{1,2})?$/
      };
      return reglas[campo] || /.*/;
    },

    marcarComoTocado(campo) {
      this.tocado[campo] = true;
      this.validarCampo(campo);
    },

    validarCampo(campo) {

        const expresiones = {
        marca: /^[a-zA-ZÀ-ÿ\s]{1,40}$/,
        // tus otras regex...
    };

    if (campo === "marca") {
        this.valid.marca = expresiones.marca.test(this.marca.trim());

        // Si el formato es válido → ejecutar AJAX
        if (this.valid.marca) {
            this.validarMarcaAjax();
        }
        return;
    }

      if (campo === 'talla' || campo === 'categoria') {
        this.valid[campo] = this[campo] !== '';
      } else {
        this.valid[campo] = this.regex(campo).test(this[campo]);
      }
    },

    getClaseValidacion(campo) {

      if (campo === "marca") {
        
        if (!this.marca) return '';

        if (!this.valid.marca) {
            return 'formulario_stock__grupo-incorrecto';
        }

        if (this.marcaExiste === true) {
            return 'formulario_stock__grupo-incorrecto';
        }

        if (this.marcaExiste === false) {
            return 'formulario_stock__grupo-correcto';
        }

        return ''; // AJAX cargando
    }


      if (!this.tocado[campo]) return '';
      return this.valid[campo] ? 'formulario_stock__grupo-correcto' : 'formulario_stock__grupo-incorrecto';
    },

    getClaseIcono(campo) {

          if (campo === "marca") {
        if (!this.valid.marca) return "fa-circle-xmark";
        if (this.marcaExiste === true) return "fa-circle-xmark";
        if (this.marcaExiste === false) return "fa-circle-check";
        return "";
    }


      return this.valid[campo] ? 'fa-circle-check' : 'fa-circle-xmark';
    },

    validarFormulario() {
      Object.keys(this.tocado).forEach(c => this.tocado[c] = true);
      Object.keys(this.valid).forEach(c => this.validarCampo(c));

      const todosValidos = Object.values(this.valid).every(v => v) && this.terminos;

      console.log('Validación:', this.valid);
      console.log('Términos:', this.terminos);
      console.log('¿Válido?:', todosValidos);

      if (!todosValidos) {
        const invalidos = Object.keys(this.valid).filter(k => !this.valid[k]);
        console.log('Campos inválidos:', invalidos);
        
        this.mensajeError = "Completa todos los campos y acepta los términos";
        this.error = true;
        setTimeout(() => this.error = false, 5000);
      }

      return todosValidos;
    },

    async enviarFormulario() {
      console.log('=== ENVIANDO FORMULARIO ===');
      
      if (!this.validarFormulario()) {
        console.log('❌ Validación falló');
        return;
      }

      console.log('✅ Validación exitosa');
      this.enviando = true;

      const datos = {
        marca: this.marca,
        talla: this.talla,
        color: this.color,
        stock: this.stock,
        precio: this.precio,
        categoria: this.categoria
      };

      try {
        console.log('📤 Enviando:', datos);
        
        const response = await fetch('guardar_calzado.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(datos)
        });

        console.log('📥 Status:', response.status);
        const resultado = await response.json();
        console.log('📥 Respuesta:', resultado);

        if (resultado.success) {
          this.exito = true;
          this.error = false;
          console.log('✅ Guardado con ID:', resultado.id);

          setTimeout(() => {
            this.exito = false;
            this.resetFormulario();
          }, 3000);
        } else {
          this.mensajeError = resultado.message;
          this.error = true;
          console.error('❌', resultado.message);
          setTimeout(() => this.error = false, 5000);
        }
      } catch (err) {
        this.mensajeError = "Error de conexión con el servidor";
        this.error = true;
        console.error('❌ Error:', err);
        setTimeout(() => this.error = false, 5000);
      } finally {
        this.enviando = false;
      }
    },

    resetFormulario() {
      this.marca = '';
      this.talla = '';
      this.color = '';
      this.stock = '';
      this.precio = '';
      this.categoria = '';
      this.terminos = false;

      Object.keys(this.valid).forEach(k => this.valid[k] = false);
      Object.keys(this.tocado).forEach(k => this.tocado[k] = false);
    }
  },

  mounted() {
    console.log('✅ Formulario Calzado cargado');
    console.log('Métodos disponibles:', Object.keys(this));
  }
}).mount("#app");