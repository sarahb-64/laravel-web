<template>
  <div>
    <nav class="bg-white border-b border-gray-100">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
          <div class="flex">
            <!-- Logo -->
            <div class="shrink-0 flex items-center">
              <Link :href="route('home')">
                <ApplicationLogo class="block h-9 w-auto" />
              </Link>
            </div>

            <!-- Navigation Links - Only show when authenticated -->
            <div v-if="$page.props.auth?.user" class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex sm:items-center">
              <NavLink :href="route('dashboard')" :active="$page.url === '/dashboard'">
                Dashboard
              </NavLink>
              
              <!-- SEO Tools Dropdown - Only show when authenticated -->
              <div class="relative">
                <button 
                  @click="isSeoMenuOpen = !isSeoMenuOpen"
                  class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 transition duration-150 ease-in-out focus:outline-none"
                  :class="isSeoMenuActive">
                  <span>Herramientas SEO</span>
                  <svg class="ml-2 -mr-0.5 h-4 w-4 transition-transform duration-200" 
                       :class="{'transform rotate-180': isSeoMenuOpen}" 
                       fill="currentColor" 
                       viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                  </svg>
                </button>

                <transition
                  enter-active-class="transition ease-out duration-200"
                  enter-from-class="opacity-0 scale-95"
                  enter-to-class="opacity-100 scale-100"
                  leave-active-class="transition ease-in duration-75"
                  leave-from-class="opacity-100 scale-100"
                  leave-to-class="opacity-0 scale-95">
                  <div 
                    v-show="isSeoMenuOpen"
                    class="origin-top-left absolute left-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50">
                    <div class="py-1">
                      <!-- SEO Meta Tags -->
                      <a :href="route('seo.meta-tags.index')" 
                        @click.prevent="navigateTo('seo.meta-tags.index')"
                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 cursor-pointer">
                        Analizador de Meta Tags
                      </a>

                      <!-- SEO Analysis -->
                      <a :href="route('seo.analysis.index')" 
                        @click.prevent="navigateTo('seo.analysis.index')"
                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 cursor-pointer">
                        Análisis SEO
                      </a>

                      <!-- Keyword Research -->
                      <a :href="route('seo.ubersuggest.index')" 
                        @click.prevent="navigateTo('seo.ubersuggest.index')"
                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 cursor-pointer">
                        Investigación de Palabras Clave
                      </a>

                      <!-- Keyword Management -->
                      <a :href="route('seo.keywords.index')" 
                        @click.prevent="navigateTo('seo.keywords.index')"
                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 cursor-pointer">
                        Gestión de Palabras Clave
                      </a>

                      <!-- A/B Testing -->
                      <a :href="route('ab-test.index')" 
                        @click.prevent="navigateTo('ab-test.index')"
                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 cursor-pointer">
                        Calculadora A/B Testing
                      </a>

                      <!-- Rank Tracker -->
                      <a :href="route('rank-tracker.index')" 
                        @click.prevent="navigateTo('rank-tracker.index')"
                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 cursor-pointer">
                        Seguimiento de Posiciones
                      </a>

                      <!-- AnswerThePublic -->
                      <a :href="route('answer-the-public')" 
                        @click.prevent="navigateTo('answer-the-public')"
                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 cursor-pointer">
                        Análisis de Búsquedas
                      </a>

                      <!-- Web Traffic Analysis -->
                      <a :href="route('web-traffic.index')" 
                        @click.prevent="navigateTo('web-traffic.index')"
                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 cursor-pointer">
                        Análisis de Tráfico Web
                      </a>

                      <!-- Open Graph Generator -->
                      <a :href="route('seo.open-graph.index')" 
                        @click.prevent="navigateTo('seo.open-graph.index')"
                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 cursor-pointer">
                        Generador Open Graph
                      </a>
                    </div>
                  </div>
                </transition>
              </div>
            </div>
          </div>

          <!-- User Menu - Only show when authenticated -->
          <div v-if="$page.props.auth?.user" class="hidden sm:flex sm:items-center sm:ml-6">
            <div class="ml-3 relative">
              <NavLink :href="route('profile.edit')" :active="$page.url === '/profile/edit'">
                {{ $page.props.auth.user.name }}
              </NavLink>
              <form @submit.prevent="logout">
                <button @click="logout" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
                  Cerrar sesión
                </button>
              </form>
            </div>
          </div>

          <!-- Login/Register Links - Show when not authenticated -->
          <div v-else class="hidden sm:flex sm:items-center sm:ml-6 space-x-4">
            <NavLink :href="route('login')">Iniciar sesión</NavLink>
            <NavLink :href="route('register')">Registrarse</NavLink>
          </div>

          <!-- Mobile menu button -->
          <div class="-mr-2 flex items-center sm:hidden">
            <button @click="showingNavigationDropdown = !showingNavigationDropdown" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition">
              <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                <path :class="{'hidden': showingNavigationDropdown, 'inline-flex': !showingNavigationDropdown }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                <path :class="{'hidden': !showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
        </div>
      </div>

      <!-- Mobile menu -->
      <div :class="{'block': showingNavigationDropdown, 'hidden': !showingNavigationDropdown}" class="sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
          <template v-if="$page.props.auth?.user">
            <NavLink :href="route('dashboard')" :active="$page.url === '/dashboard'">
              Dashboard
            </NavLink>
            
            <div class="px-4">
              <div class="font-medium text-sm text-gray-500">Herramientas SEO</div>
              <div class="mt-1 space-y-1">
                <NavLink :href="route('seo.meta-tags.index')" :active="$page.url === '/seo/meta-tags'">
                  Analizador de Meta Tags
                </NavLink>
                <NavLink :href="route('seo.analysis.index')" :active="$page.url === '/seo/analysis'">
                  Análisis SEO
                </NavLink>
                <NavLink :href="route('seo.ubersuggest.index')" :active="$page.url === '/seo/ubersuggest'">
                  Investigación de Palabras Clave
                </NavLink>
                <NavLink :href="route('seo.keywords.index')" :active="$page.url === '/seo/keywords'">
                  Gestión de Palabras Clave
                </NavLink>
                <!-- Other mobile menu items... -->
              </div>
            </div>

            <div class="pt-4 pb-1 border-t border-gray-200">
              <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ $page.props.auth.user.name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ $page.props.auth.user.email }}</div>
              </div>

              <div class="mt-3 space-y-1">
                <NavLink :href="route('profile.edit')">Perfil</NavLink>
                <form method="POST" @submit.prevent="logout">
                  <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                    Cerrar sesión
                  </button>
                </form>
              </div>
            </div>
          </template>
          <template v-else>
            <NavLink :href="route('login')">Iniciar sesión</NavLink>
            <NavLink :href="route('register')">Registrarse</NavLink>
          </template>
        </div>
      </div>
    </nav>

    <main>
      <slot />
    </main>
  </div>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount, computed } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import NavLink from '@/Components/NavLink.vue';
import { route } from 'ziggy-js';

const showingNavigationDropdown = ref(false);
const isSeoMenuOpen = ref(false);
const page = usePage();

const isSeoMenuActive = computed(() => {
  const currentRoute = page.url;
  return currentRoute && (currentRoute.includes('seo') || currentRoute.includes('rank-tracker'))
    ? 'border-indigo-500 text-gray-900 focus:border-indigo-700' 
    : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:text-gray-700 focus:border-gray-300';
});

const navigateTo = (routeName) => {
  isSeoMenuOpen.value = false;
  router.visit(route(routeName));
};

const handleClickOutside = (event) => {
  if (!event.target.closest('.relative')) {
    isSeoMenuOpen.value = false;
  }
};

onMounted(() => {
  document.addEventListener('click', handleClickOutside);
});

onBeforeUnmount(() => {
  document.removeEventListener('click', handleClickOutside);
});

const logout = () => {
  router.post(route('logout'));
};
</script>

<style scoped>
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.2s;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
}
</style>