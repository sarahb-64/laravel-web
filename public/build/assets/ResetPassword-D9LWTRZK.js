import{x as _,h as r,d as f,o as g,b as o,u as e,g as x,w as l,a,e as b,n as v,f as V,F as y}from"./welcome-oG7xEMQG.js";import{_ as P}from"./AuthenticatedLayout-DhiNIsmy.js";import"./ResponsiveNavLink-5A9uafSX.js";const k={class:"py-12"},I={class:"max-w-md mx-auto sm:px-6 lg:px-8"},h={class:"mt-4"},B={class:"mt-4"},C={class:"flex items-center justify-end mt-4"},S={__name:"ResetPassword",props:{email:String,token:String},setup(u){const d=u,s=_({token:d.token,email:d.email,password:"",password_confirmation:""}),c=()=>{s.post(route("password.store"),{onFinish:()=>s.reset("password","password_confirmation")})};return(R,t)=>{const i=r("InputLabel"),m=r("TextInput"),p=r("InputError"),w=r("PrimaryButton");return g(),f(y,null,[o(e(x),{title:"Reset Password"}),o(P,null,{header:l(()=>t[2]||(t[2]=[a("h2",{class:"font-semibold text-xl text-gray-800 leading-tight"}," Reset Password ",-1)])),default:l(()=>[a("div",k,[a("div",I,[a("form",{onSubmit:b(c,["prevent"])},[a("div",h,[o(i,{for:"password",value:"Password"}),o(m,{id:"password",modelValue:e(s).password,"onUpdate:modelValue":t[0]||(t[0]=n=>e(s).password=n),type:"password",class:"mt-1 block w-full",required:"",autocomplete:"new-password"},null,8,["modelValue"]),o(p,{class:"mt-2",message:e(s).errors.password},null,8,["message"])]),a("div",B,[o(i,{for:"password_confirmation",value:"Confirm Password"}),o(m,{id:"password_confirmation",modelValue:e(s).password_confirmation,"onUpdate:modelValue":t[1]||(t[1]=n=>e(s).password_confirmation=n),type:"password",class:"mt-1 block w-full",required:"",autocomplete:"new-password"},null,8,["modelValue"]),o(p,{class:"mt-2",message:e(s).errors.password_confirmation},null,8,["message"])]),a("div",C,[o(w,{class:v(["ms-4",{"opacity-25":e(s).processing}]),disabled:e(s).processing},{default:l(()=>t[3]||(t[3]=[V(" Reset Password ")])),_:1},8,["class","disabled"])])],32)])])]),_:1})],64)}}};export{S as default};
