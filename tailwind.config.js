module.exports = { 
  content: [ 
    "./app/views/adminView**/*.php", 
    "./app/views/userView**/*.php", 
    "./app/routers**/*.php", 
    "./public/**/*.html" 
  ], 
  theme: { 
    extend: { 
 
      fontFamily: { 
        "product-sans": ["var(--font-product-sans)"], 
      }, 
 
      colors: { 
        "dark-blue": "#001D6E", 
        "my-blue": "#7FB5FF", 
        "light-blue": "rgba(196, 221, 255, 0.1)", 
        "my-green": "#00AC4F", 
        "light-green": "rgba(0, 172, 79, 0.35)", 
        "my-red": "#DF0404", 
        "light-red": "#FFC5C5", 
        "my-grey": "#606060", 
        "light-grey": "rgba(217, 217, 217, 0.35)" 
      }, 
 
      boxShadow: { 
        "my-shadow": '0px 4px 7px 0px rgba(0, 0, 0, 0.05)', 
      }, 
 
    }, 
  }, 
  plugins: [], 
};