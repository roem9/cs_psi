import * as React from "react";

function IconStairsUp({
  size = 24,
  color = "currentColor",
  stroke = 2,
  ...props
}) {
  return <svg className="icon icon-tabler icon-tabler-stairs-up" width={size} height={size} viewBox="0 0 24 24" strokeWidth={stroke} stroke={color} fill="none" strokeLinecap="round" strokeLinejoin="round" {...props}><path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M4 20h4v-4h4v-4h4v-4h4" /><path d="M4 11l7 -7v4m-4 -4h4" /></svg>;
}

export default IconStairsUp;