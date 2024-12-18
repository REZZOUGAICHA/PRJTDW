/** @type {import('tailwindcss').Config} */
module.exports = {
  content: 
  [
    "./*.php", // Apply to PHP files in the root directory
    "./**/*.php", // Apply to all PHP files in subdirectories
  ],
  theme: {
    extend: {},
  },
  plugins: [],
}

