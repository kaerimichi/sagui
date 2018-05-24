var Login = {
    delimiters: ['${', '}'],
    data: function () {
        return {
            elPwd: true,
            password: '',
            email: '',
            rules: {
                required: (value) => !!value || 'Required.',
                email: (value) => {
                    const pattern = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
                    return pattern.test(value) || 'Invalid e-mail.'
                }
            }
        }
    },
    template:
        '    <v-layout row wrap justify-center >\n' +
        '      <v-flex xs12 sm6>\n' +
        '        <v-card class="elevation-10">\n' +
        '          <v-toolbar color="teal">\n' +
        '            <v-toolbar-title class="white--text">Login</v-toolbar-title>\n' +
        '          </v-toolbar>\n' +
        '          <v-container fluid>\n' +
        '            <v-layout row wrap>\n' +
        '              <v-flex xs12>\n' +
        '                <v-text-field\n' +
        '                  v-model="email"\n' +
        '                  :rules="[rules.required, rules.email]"\n' +
        '                  label="E-mail"\n' +
        '                ></v-text-field>\n' +
        '              </v-flex>\n' +
        '              <v-flex xs12>\n' +
        '                <v-text-field\n' +
        '                  v-model="password"\n ' +
        '                  :append-icon="elPwd ? \'visibility\' : \'visibility_off\'"' +
        '                  :append-icon-cb="() => (elPwd = !elPwd)"' +
        '                  :type="elPwd ? \'password\' : \'text\'"' +
        '                  name="input-10-1"' +
        '                  :rules="[rules.required]"\n' +
        '                  label="Enter your password"\n' +
        '                ></v-text-field>\n' +
        '              </v-flex>\n' +
        '            </v-layout>\n' +
        '          </v-container>\n' +
        '        </v-card>\n' +
        '      </v-flex>\n' +
        '    </v-layout>\n'
}

const router = new VueRouter({
    routes: [
        { path: '/', component: Login, name: 'login' },
    ],
})

new Vue({
    el: '#app',
    components: {
        'login': Login
    },
    router: router,
})