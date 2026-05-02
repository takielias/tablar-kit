import * as LitepickerModule from "litepicker";

function resolveCtor(obj, depth = 0) {
    if (!obj || depth > 4) {
        return null;
    }
    if (typeof obj === "function") {
        return obj;
    }
    return (
        resolveCtor(obj.default, depth + 1) ||
        resolveCtor(obj.Litepicker, depth + 1)
    );
}

const Litepicker = resolveCtor(LitepickerModule) || window.Litepicker;

if (typeof Litepicker === "function") {
    window.Litepicker = Litepicker;
} else {
    console.error("[litepicker] constructor not found", LitepickerModule);
}
