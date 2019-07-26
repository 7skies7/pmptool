<template>
    
  <v-app style="background:transparent!important">
    <div class="text-xs-center">
      <v-menu
        v-model="menu"
        :close-on-content-click="false"
        :nudge-width="200"
        offset-x
      >
        <template v-slot:activator="{ on }">
          <v-btn
            color="indigo"
            dark
            v-on="on"
          >
            {{ userName }}
          </v-btn>
        </template>
  
        <v-card>
          <v-list>
            <v-list-tile avatar>
              <v-list-tile-avatar>
                <img src="/images/User.png" alt="John">
              </v-list-tile-avatar>
  
              <v-list-tile-content>
                <v-list-tile-title>{{ userName}}</v-list-tile-title>
                <v-list-tile-sub-title>{{ this.userdetails.email }}</v-list-tile-sub-title>
              </v-list-tile-content>
  
<!--               <v-list-tile-action>
                <v-btn
                  :class="fav ? 'red--text' : ''"
                  icon
                  @click="fav = !fav"
                >
                  <v-icon>favorite</v-icon>
                </v-btn>
              </v-list-tile-action> -->
            </v-list-tile>
          </v-list>
  
          <v-divider></v-divider>
  
          <v-list>
            <v-list-tile>
              <v-list-tile-action>
                <v-switch v-model="message" color="purple"></v-switch>
              </v-list-tile-action>
              <v-list-tile-title>Enable messages</v-list-tile-title>
            </v-list-tile>
  
            <v-list-tile>
              <v-list-tile-action>
                <v-switch v-model="hints" color="purple"></v-switch>
              </v-list-tile-action>
              <v-list-tile-title>Enable hints</v-list-tile-title>
            </v-list-tile>
          </v-list>
  
          <v-card-actions>
            <v-spacer></v-spacer>
  
            <v-btn flat @click="menu = false">Cancel</v-btn>
            <slot></slot>
          </v-card-actions>
        </v-card>
      </v-menu>
    </div>
  </v-app>

</template>

<script>
    import Acl from '../models/Acl';
    export default {
        
        data() {
            return {
                fav: true,
                menu: false,
                message: false,
                hints: true,
                userdetails:[],
            }
        },
        created() {
            Acl.getUserDetails(userdetails => this.userdetails = userdetails);
            console.log(this.userdetails);
        },
        computed: {
            userName: function() {
                return this.userdetails.first_name + ' ' + this.userdetails.last_name
            }
        }
    }
</script>
