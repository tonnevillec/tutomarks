import React from "react";

export function Icon ({icon, margin}) {
    return <i className={"bi bi-" + icon + " " + margin} aria-hidden='true'></i>
}