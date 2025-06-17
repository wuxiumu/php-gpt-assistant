/** @type {import('tailwindcss').Config} */
export default {
  darkMode: 'class', // 必须有这个选项，否则无法切换主题
  content: [
    "./index.html",
    "./src/**/*.{vue,js,ts,jsx,tsx}",
  ],
  theme: {
    extend: {},
  },
  plugins: [],
}

