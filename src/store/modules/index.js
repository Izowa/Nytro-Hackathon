
import camelCase from 'lodash/camelCase';

const requireModule = require.context(".", false, /\.js$/);

const modules = {};

requireModule.keys().forEach(element => {
    if (element === "./index.js") return;
    const moduleName = camelCase(element.replace(/(\.\/|\.js)/g, ""))

    modules[moduleName] = requireModule(element).default;
});

export default modules;