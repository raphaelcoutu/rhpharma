import "../css/app.css";
import "./bootstrap";

import { createInertiaApp } from "@inertiajs/react";

const appName = import.meta.env.VITE_APP_NAME || "RHPharma";

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    pages: {
        path: "./pages",
        extension: ".jsx",
    },
    progress: {
        color: "#4B5563",
    },
});
