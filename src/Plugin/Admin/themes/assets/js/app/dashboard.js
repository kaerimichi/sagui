var Dashboard = {
  delimiters: ['${', '}'],
  data: () => {
    return {
      items: [
        { title: 'Home', icon: 'dashboard' },
        { title: 'About', icon: 'question_answer' }
      ],
      right: null
    }
  },
  methods: {
  },
  template:
    '    <div><v-navigation-drawer permanent>\n' +
    '        <v-toolbar flat>\n' +
    '          <v-list>\n' +
    '            <v-list-tile>\n' +
    '              <v-list-tile-title class="title">\n' +
    '                Application\n' +
    '              </v-list-tile-title>\n' +
    '            </v-list-tile>\n' +
    '          </v-list>\n' +
    '        </v-toolbar>\n' +
    '        <v-divider></v-divider>\n' +
    '        <v-list dense class="pt-0">\n' +
    '          <v-list-tile v-for="item in items" :key="item.title" @click="">\n' +
    '            <v-list-tile-action>\n' +
    '              <v-icon>${ item.icon }</v-icon>\n' +
    '            </v-list-tile-action>\n' +
    '            <v-list-tile-content>\n' +
    '              <v-list-tile-title>${ item.title }</v-list-tile-title>\n' +
    '            </v-list-tile-content>\n' +
    '          </v-list-tile>\n' +
    '        </v-list>\n' +
    '      </v-navigation-drawer>\n' +
    '\n' +
    // '      <v-toolbar app></v-toolbar>\n' +
    '        <v-content>\n' +
    '          <v-container fluid>\n' +
    '            <router-view></router-view>\n' +
    '          </v-container>\n' +
    '        </v-content>\n' +
    '        <v-footer app></v-footer></div>'
}