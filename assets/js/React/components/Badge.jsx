import React from "react";

export function Badge ({text, back, icon, title}) {
    return <span className={"badge " + back + " " + text + " font-semibold me-1"}>
        {icon.length > 0 && <i className={"bi bi-" + icon + " me-1"}></i>} {title}
    </span>
}