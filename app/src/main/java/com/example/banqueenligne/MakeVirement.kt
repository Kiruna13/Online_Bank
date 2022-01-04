package com.example.banqueenligne

import android.content.Intent
import android.os.Bundle
import android.view.View
import android.view.Window
import androidx.appcompat.app.AppCompatActivity


class MakeVirement : AppCompatActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        requestWindowFeature(Window.FEATURE_NO_TITLE)
        supportActionBar?.hide()
        setContentView(R.layout.make_virement)
    }
    fun toVersCompte(view : View?){
        val intent = Intent(this@MakeVirement, VersCompte::class.java)
        startActivity(intent)
    }
    fun toDepuisCompte(view : View?){
        val intent = Intent(this@MakeVirement, DepuisCompte::class.java)
        startActivity(intent)
    }
}