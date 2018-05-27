var Register = {
  delimiters: ['${', '}'],
  data: () => {
    return {
      valid: false,
      name: '',
      nameRules: [
        v => !!v || 'Name is required',
        v => (v && v.length <= 100) || 'Name must be less than 100 characters'
      ],
      email: '',
      emailRules: [
        v => !!v || 'E-mail is required',
        v => /^\w+([.-]?\w+)*@\w+([.-]?\w+)*(\.\w{2,3})+$/.test(v) || 'E-mail must be valid'
      ],
      password: '',
      passwordRules: [
        v => !!v || 'Password is required',
        v => v && v.length <= 30 || 'Password must be less than 30 characters',
        v => v && !(v.length < 4) || 'Password must be more than 4 characters'
      ],
    }
  },
  methods: {
    submit () {
      if (this.$refs.form.validate()) {
        this.$store.dispatch('attemptRegisterAdmin', {name: this.name, email: this.email, password: this.password})
      }
    },
    clear () {
      this.name = ''
      this.email = ''
      this.password = ''
      this.valid = false
      this.$refs.form.reset()
    }
  },
  template:
    '  <v-form ref="form" v-model="valid" lazy-validation>\n' +
    '    <v-text-field\n' +
    '      v-model="name"\n' +
    '      :rules="nameRules"\n' +
    '      :counter="100"\n' +
    '      label="Name"\n' +
    '      required\n' +
    '    ></v-text-field>\n' +
    '    <v-text-field\n' +
    '      v-model="email"\n' +
    '      :rules="emailRules"\n' +
    '      label="E-mail"\n' +
    '      required\n' +
    '    ></v-text-field>\n' +
    '    <v-text-field\n' +
    '      v-model="password"\n' +
    '      :rules="passwordRules"\n' +
    '      :counter="30"\n' +
    '      label="Password"\n' +
    '      required\n' +
    '    ></v-text-field>\n' +
    '    <v-btn\n' +
    '      :disabled="!valid"\n' +
    '      @click="submit"\n' +
    '    >\n' +
    '      submit\n' +
    '    </v-btn>\n' +
    '    <v-btn @click="clear">clear</v-btn>\n' +
    '  </v-form>'
}