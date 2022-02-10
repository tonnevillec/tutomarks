import React from "react";

export function Image ({imgSrc, imgClass, imgAlt, imgHeight, imgWidth}) {
    return <img src={imgSrc}
                className={imgClass}
                alt={imgAlt}
                width={imgWidth}
                height={imgHeight}
    ></img>
}